<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Models\UserBalanceLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class DriveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Drive $drive, Project $project)
    {
        $drive = Drive::where('project_id', $request->route('project_id'))
            ->whereNull('parent_id')->with(['childFolders'])->get();

        $project_name = $project->where('id', $request->route('project_id'))->firstOrFail()->name;

        return view('projects.drive.index', compact('drive', 'project_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {;
        if (!ProjectMembersController::userInProject($request->route('project_id'))) {
            return redirect()->to('/')->with('status', '你可能正在尝试越权。');
        }

        $path = $request->path;
        if (is_null($request->path)) {
            $path = '/';
        }

        return view('projects.drive.create', compact('path'));
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

        $this->validate($request, ['path' => 'required']);

        if ($request->name == null && !$request->hasFile('file')) {
            return redirect()->back()->with('error', 'no name or file.');
        }
        $path = $request->path;
        $drive = new Drive();
        // if ($drive->where);
        $drive->name = $request->name;

        $last_path = $path . '/';
        $last_path = Str::replaceLast('/', '', $last_path);
        $drive->path = $path . '/' . $request->name;

        // 检查字符串第一个是否为/
        if (Str::substr($drive->path, 0, 1) == '/') {
            $drive->path = ltrim($drive->path, '/');
        }

        if (Str::substr($drive->path, 0, 1) == '/') {
            // 再移除一次
            $drive->path = Str::replaceFirst('/', '', $drive->path);
        }

        $drive->path_hash = md5($drive->path);

        $drive->mimetype = null; // 为空则是目录
        $drive->project_id = $request->project_id;

        $drive_where = $drive->where('project_id', $request->project_id);
        $drive_path_hash_where = $drive_where->where('path_hash', md5($last_path));

        // 获得上级目录path_hash，然后获得id
        if (!$drive_path_hash_where->exists()) {
            $parent_id = null;
        } else {
            $parent_id = $drive_path_hash_where->firstOrFail()->id;
        }

        $drive->parent_id = $parent_id;
        $drive->cost_method = 'project';


        // 检查目录或文件是否已经存在
        if (empty($drive->path) && $request->hasFile('file')) {
            return redirect()->back()->with('error', '无法上传文件至 /');
        }


        // 检查是否有文件
        if ($request->hasFile('file')) {
            //
            $drive->name = $request->file('file')->getClientOriginalName();
            $drive->path = $path . '/' . $request->name . $request->file('file')->getClientOriginalName();
            $drive->size = $request->file('file')->getSize() / 1000000;
            $fileName = $request->file('file')->store('project/' . Auth::id() . '/drive', 'cosv5');
            $fileName = explode('/', $fileName);
            $fileName = $fileName[count($fileName) - 1];
            $drive->fileName = $fileName;
            $drive->mimetype = $request->file('file')->getMimeType();
        }

        $drive->save();

        return redirect()->back()->with('status', '保存成功.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Drive\Drive  $drive
     * @return \Illuminate\Http\Response
     */
    public function show(Drive $drive, Request $request)
    {
        // $path = $request->path;
        // if (Str::substr($drive->path, -1, 1) == '/') {
        //     $path = Str::replaceLast('/', '', $path);
        // }

        // $drive_where = $drive->where('project_id', $request->route('project_id'));
        // $drive_path_hash_where = $drive_where->where('path_hash', md5($path));

        // $parent_id = $drive_path_hash_where->firstOrFail()->id;
        // $drive = $drive_where->where('parent_id', $parent_id)->get();
        // return view('projects.drive.index', compact('drive', 'path'));
    }

    public function files(Drive $drive, Request $request, Project $project)
    {
        if (!ProjectMembersController::userInProject($request->route('project_id'))) {
            return redirect()->to('/')->with('status', '你可能正在尝试越权。');
        }

        $project_name = $project->where('id', $request->route('project_id'))->firstOrFail()->name;

        $path = $request->path;

        if (Str::substr($drive->path, -1, 1) == '/') {
            $path = Str::replaceLast('/', '', $path);
        }

        $drive_where = $drive->where('project_id', $request->route('project_id'));
        $drive_path_hash_where = $drive_where->where('path_hash', md5($path));

        $parent_id = $drive_path_hash_where->firstOrFail()->id;


        $drive = $drive->where('project_id', $request->route('project_id'))->where('parent_id', $parent_id)->get();

        return view('projects.drive.index', compact('drive', 'path', 'project_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Drive\Drive  $drive
     * @return \Illuminate\Http\Response
     */
    public function edit(Drive $drive, Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Drive\Drive  $drive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Drive $drive)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Drive\Drive  $drive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Drive $drive, Request $request)
    {
        if (!ProjectMembersController::userInProject($request->route('project_id'))) {
            return redirect()->to('/')->with('status', '你可能正在尝试越权。');
        }

        $drive_where = $drive->where('project_id', $request->route('project_id'));

        // 先检查目录下方有无目录，如果有，则禁止删除
        if ($drive_where->where('parent_id', $request->route('storage'))->where('mimetype', null)->exists()) {
            return redirect()->back()->with('status', '📁: 你需要先删除该文件夹下的所有文件夹。');
        }

        // 遍历文件
        $arr = $drive->where('project_id', $request->route('project_id'))->where('parent_id', $request->route('storage'))->get();

        foreach ($arr as $item) {
            if ($item->mimetype != null) {
                //  'project/' . $data->project_id . '/' . $path . '/' . $fn,
                // 删除文件
                $path = 'project/' . $item->project_id . '/drive/' . $item->fileName;
                Storage::disk('cosv5')->delete($path);
                $drive->where('id', $item->id)->delete();
            } else {
                $drive->where('id', $item->id)->delete();
            }
        }

        $drive_where = $drive->where('id', $request->route('storage'))->where('project_id', $request->route('project_id'));
        if (!$drive_where->where('mimetype', null)->exists()) {
            // 是文件, 获取fileName
            $fileName = $drive->where('id', $request->route('storage'))->where('project_id', $request->route('project_id'))->firstOrFail()->fileName;

            $path = 'project/' . $request->route('project_id') . '/drive/' . $fileName;
            Storage::disk('cosv5')->delete($path);
        }



        $drive->where('project_id', $request->route('project_id'))->where('id', $request->route('storage'))->delete();
        return redirect()->back()->with('status', '删除成功。');
    }

    public function checkFileExists($drive, $fn)
    {
        if ($drive->where('fileName', $fn)->exists()) {
            return true;
        }
    }

    public function checkPrivate($drive, $project_id)
    {
        // 检查是否以_开头
        if (Str::startsWith($drive->name, '_')) {
            // 确实以_开头
            // 再检查是否为当前用户拥有
            if (!ProjectMembersController::userInProject($project_id)) {
                return false;
            }
        }

        return true;
    }

    public static function getUrl($drive, $path, $fn)
    {
        $data = $drive->where('fileName', $fn)->firstOrFail();

        $url = Storage::temporaryUrl(
            'project/' . $data->project_id . '/' . $path . '/' . $fn,
            now()->addMinutes(1),
            [
                'ResponseContentType' => 'application/octet-stream',
                "ResponseContentDisposition" => "attachment;filename=\"{$data->name}\""
            ]
        );
        return $url;
    }

    public static function getUrlByPath($path)
    {
        $url = Storage::url($path);

        return $url;
    }

    public function route_download(Drive $drive, Request $request)
    {
        $fileName = $request->route('name');

        if (!$this->checkFileExists($drive, $fileName)) {
            abort(404);
        }

        $drive_data = $drive->where('fileName', $fileName)->firstOrFail();

        if (!$this->checkPrivate($drive_data, $drive_data->project_id)) {
            return redirect()->back()->with('status', '文件是被保护着的。');
        }

        $ext = explode('.', $fileName);
        $ext = $ext[count($ext) - 1];
        $check_ext = in_array($ext, ['gif', 'jpg', 'png'], true);

        if ($check_ext) {
            $url = $this->getUrl($drive, 'drive', $fileName);
            return redirect()->to($url);
        } else {
            $userBalanceLog = new UserBalanceLog();
            // 通过fileName查看文件大小，目录，然后计算费用，再扣费
            // 计费(1MB = 0.01)
            $cost = $drive_data->size / 1;
            if ($userBalanceLog->cost(Auth::id(), $cost, 'You download a file.')) {
                $url = $this->getUrl($drive, 'drive', $fileName);
                return redirect()->to($url);
            } else {
                return redirect()->route('download.view', $fileName)->with('status', '你没有足够的积分来下载这个文件。');
            }
        }
    }

    public function view(Drive $drive, User $user, Request $request)
    {

        $fileName = $request->route('name');
        $drive_where_fileName = $drive->where('fileName', $fileName)->with('project');
        if (!$drive_where_fileName->exists()) {
            return abort(404);
        }

        // 通过fileName查看文件大小，目录，然后计算费用，再扣费
        $drive_data = $drive_where_fileName->firstOrFail();
        $name = $drive_data->name;

        // 检查是否以_开头
        if (!$this->checkPrivate($drive_data, $drive_data->project_id)) {
            return redirect()->back()->with('status', '文件是被保护着的。');
        }


        $size = $drive_data->size;

        $ext = explode('.', $fileName);
        $ext = $ext[count($ext) - 1];
        $check_ext = in_array($ext, ['gif', 'jpg', 'png'], true);

        if ($check_ext) {
            $cost = 0;
        } else {
            // 计费(1MB = 0.01)
            $cost = $size / 1;
        }

        // 查询余额
        $balance = UserBalanceLog::getBalance();
        $left = $balance - $cost;

        $data = (object)[
            'balance' => $balance,
            'cost' => $cost,
            'left' => $left,
            'name' => $name,
            'fileName' => $fileName,
            'size' => $size,
            'projectName' => $drive_data->project->name,
            'mimetype' => $drive_data->mimetype,
        ];

        return view('projects.drive.download', compact('data'));
    }
}
