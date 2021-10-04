<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->simplePaginate(50);

        return view('images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProjectMember $member)
    {
       // 列出项目
       $projects = $member->where('user_id', Auth::id())->with('project')->get();

       return view('images.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!ProjectMembersController::userInProject($request->route('project_id'))) {
            return redirect()->to('/')->with('status', '你可能正在尝试越权。');
        }
    }

    public function editor_md_upload(Request $request)
    {
        $json = [
            'success' => 0,
            'message' => '失败原因为：未知',
            'url' => '',
        ];
        if ($request->hasFile('editormd-image-file')) {
            //
            $file = $request->file('editormd-image-file');
            $data = $request->all();
            $rules = [
                'editormd-image-file'    => 'max:5120',
            ];
            $messages = [
                'editormd-image-file.max'    => '文件过大,文件大小不得超出5MB',
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->passes()) {
                $realPath = $file->getRealPath();
                $destPath = 'uploads/content/';
                is_dir($savePath) || mkdir($savePath);  //如果不存在则创建目录
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();

                $check_ext = in_array($ext, ['gif', 'jpg', 'png'], true);

                if ($check_ext) {
                    $uniqid = uniqid() . '_' . date('s');
                    $oFile = $uniqid . 'o.' . $ext;
                    $fullfilename = '/' . $savePath . '/' . $oFile;  //原始完整路径
                    if ($file->isValid()) {
                        $uploadSuccess = $file->move($savePath, $oFile);  //移动文件
                        switch (config('editor.waterMarkType')) {
                            case 'text':
                                add_text_water(public_path($fullfilename), config('editor.textWaterContent'), config('editor.textWaterColor'));
                                break;
                            case 'image':
                                add_image_water(public_path($fullfilename), config('editor.imageWaterPath'));
                                break;
                        }
                        $oFilePath = $savePath . '/' . $oFile;
                        $json = array_replace($json, ['success' => 1, 'url' => $fullfilename]);
                    } else {
                        $json = array_replace($json, ['success' => 0, 'meassge' => '失败原因为：文件校验失败']);
                    }
                } else {
                    $json = array_replace($json, ['success' => 0, 'message' => '失败原因为：文件类型不允许,请上传常规的图片（gif|jpg|png）文件']);
                }
            } else {
                $json = format_json_message($validator->messages(), $json);
            }
        }
        return response()->json($json);
    }

    private function add_image()
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
