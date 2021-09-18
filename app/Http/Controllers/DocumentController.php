<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Document;
use App\Models\DocumentTag;
use App\Models\DocumentLike;
use Illuminate\Http\Request;
use App\Models\UserBalanceLog;
use App\Models\DocumentComment;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Document $documents)
    {
        $documents = $documents->where('visibility', true)->simplePaginate(50);
        return view('documents.index', compact('documents'));
    }

    public function my(Document $documents)
    {
        $documents = $documents->where('user_id', Auth::id())->simplePaginate(50);
        return view('documents.index', compact('documents'))->with('config', 'my');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Document $document)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $document->title = $request->title;
        $document->description = $request->description;
        $document->user_id = Auth::id();
        $document->save();

        return redirect()->route('documents.edit', $document->id)->with('status', '非常感谢您对我们社区的贡献。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 获取信息

        // 检测是否为该用户的
        $document = Document::where('id', $id)->with(['user'])->firstOrFail();

        if ($document->user_id == Auth::id() || $document->visibility) {
            $doc_where = DocumentLike::where('document_id', $document->id)->where('user_id', Auth::id());
            if ($doc_where->exists()) {
                $is_liked = DocumentLike::where('document_id', $document->id)->where('user_id', Auth::id())->first()->is_liked;
            } else {
                $is_liked = 0;
            }

            // views++
            $document->increment('views');
            return view('documents.show', compact('document', 'is_liked'));
        } else {
            return redirect()->route('documents.my')->with('status', '内容不可见。');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 获取信息
        $document = Document::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 获取信息
        $document = Document::where('id', $id)->where('user_id', Auth::id());
        if ($document->exists()) {
            if (!is_null($request->content)) {
                $this->validate($request, [
                    'content' => 'required'
                ]);

                $document->update([
                    'content' => $request->content
                ]);
            }

            if (!is_null($request->visibility)) {
                $this->validate($request, [
                    'visibility' => 'boolean'
                ]);

                $document->update([
                    'visibility' => $request->visibility
                ]);
            }

            if (!is_null($request->title)) {
                $this->validate($request, [
                    'title' => 'required'
                ]);

                $document->update([
                    'title' => $request->title
                ]);
            }

            if (!is_null($request->description)) {
                $this->validate($request, [
                    'description' => 'required'
                ]);

                $document->update([
                    'description' => $request->description
                ]);
            }


            if (!is_null($request->image_url)) {
                $this->validate($request, [
                    'image_url' => 'url'
                ]);

                $document->update([
                    'image_url' => $request->image_url
                ]);
            }

            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'error',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 获取信息
        $document = Document::where('id', $id)->where('user_id', Auth::id());
        if ($document->exists()) {
            $document->delete();
            return redirect()->back()->with('status', '删除成功。');
        } else {
            return redirect()->back()->with('status', '删除失败。');
        }
    }

    public function like($id)
    {
        $documentLike = new DocumentLike();
        // 获取信息
        $documentLike_data = $documentLike::where('document_id', $id)->with('document')->where('user_id', Auth::id());
        if ($documentLike_data->exists()) {
            // 已经赞过，将点赞切换
            $data = $documentLike->where('document_id', $id)->where('user_id', Auth::id())->firstOrFail();
            if ($data->is_liked) {
                // 切换为未点赞
                $data = $documentLike->where('document_id', $id)->where('user_id', Auth::id())->update(['is_liked' => 0]);
            } else {
                $data = $documentLike->where('document_id', $id)->where('user_id', Auth::id())->update(['is_liked' => 1]);
            }

            return response()->json(['status' => 'success']);
        } else {
            $document = Document::where('id', $id);
            if (!$document->exists()) {
                return response()->json(['status' => 'error']);
            }
            $documentLike->document_id = $id;
            $documentLike->user_id = Auth::id();
            $documentLike->type = 'like';
            $documentLike->is_liked = true;
            $documentLike->save();

            $doc_user_id = $document->firstOrFail()->user_id;
            if ($doc_user_id !== Auth::id()) {
                // 给作者打钱
                $userBalanceLog = new UserBalanceLog();
                $userBalanceLog->charge($doc_user_id, 1, 'Document like.');
                Message::send('嗨，' . Auth::user()->name . " 赞了你的文档 " . $documentLike->document->title , $doc_user_id);
            }

            return response()->json(['status' => 'success']);
        }
    }

    public function search(Request $request)
    {
        $str = $request->title;
        $documents = Document::search($str)->where('visibility', 1)->simplePaginate(100);
        return view('documents.index', compact('documents'));
    }
}
