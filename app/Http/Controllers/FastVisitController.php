<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\FastVisit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use App\Models\FastVisitDomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FastVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fastVisits = FastVisit::with(['server', 'project', 'domain'])->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->simplePaginate(50);

        return view('fastVisit.index', compact('fastVisits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project, ProjectMember $member, FastVisitDomain $domain)
    {
        // 列出域名
        $domains = $domain->orderBy('balance')->get();

        return view('fastVisit.create', compact('domains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FastVisit $fastVisit, FastVisitDomain $domain)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'domain_id' => 'required',
            'uri' => 'required',
            'name' => 'required',
        ]);

        $enable_ad = $request->enable_ad ?? 0;

        $str = null;
        while (true) {
            $str = Str::random(10);
            if (!$fastVisit->where('slug', $str)->exists()) {
                break;
            }
        }

        $project_id = $request->project_id;
        if (ProjectMembersController::userInProject($project_id)) {
            if (!$domain->where('id', $request->domain_id)->exists()) {
                return redirect()->back()->with('status', '域名不可用。');
            }

            $fastVisit->name = $request->name;
            $fastVisit->slug = $str;
            $fastVisit->uri = $request->uri;
            $fastVisit->status = 1;
            $fastVisit->show_ad = $enable_ad;
            $fastVisit->project_id = $request->project_id;
            $fastVisit->domain_id = $request->domain_id;
            $fastVisit->times = 0;
            $fastVisit->save();

            ProjectActivityController::save($project_id, '新建了 快捷访问 ' . $fastVisit->name . '。');


            return redirect()->route('fastVisit.index')->with('status', '新建成功。');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fastVisitDomain = new FastVisitDomain();
        if (!$fastVisitDomain->where('domain', request()->getHttpHost())->exists()) {
            abort(404);
        }
        $ip = request()->ip();
        $key = 'lae_c_' . $ip . '_fv_v_' . md5($id);
        if (!Cache::has($key)) {
            Cache::put($key, 1, 43200);
        } else {
            Cache::increment($key);
        }
        $fastVisit = new FastVisit();
        $fastVisit_sql = $fastVisit->where('slug', $id);
        $data = $fastVisit_sql->firstOrFail();

        if (Cache::get($key) > 1) {
            // 阻止用户获得积分
        } else {
            $fastVisit_sql->increment('times');
            if ($data->show_ad) {
                Project::charge($data->project->id, $data->domain->balance);
            }
        }


        if ($data->show_ad) {
            return view('fastVisit.public', compact('data'));
        } else {
            return redirect()->away($data->uri);
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
        $fastVisit = FastVisit::where('id', $id)->firstOrFail();
        if (ProjectMembersController::userInProject($fastVisit->project_id)) {
            return view('fastVisit.edit', compact('fastVisit'));
        } else {
            return redirect()->back()->with('status', '无权限访问。');
        }
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
        $this->validate($request, [
            'uri' => 'required',
            'name' => 'required',
            'enable_ad' => 'nullable|boolean',
        ]);

        $enable_ad = $request->enable_ad ?? 0;

        $fastVisit = FastVisit::where('id', $id)->firstOrFail();
        if (ProjectMembersController::userInProject($fastVisit->project_id)) {
            FastVisit::where('id', $fastVisit->id)->update([
                'name' => $request->name,
                'uri' => $request->uri,
                'show_ad' => $enable_ad
            ]);

            ProjectActivityController::save($fastVisit->project_id, '编辑了 快捷访问 ' . $fastVisit->name . '，新的名称为: ' . $request->name . '，新的地址为: ' . $request->uri);

            return redirect()->back()->with('status', '快捷访问 已修改。');
        } else {
            return redirect()->back()->with('status', '无权限访问。');
        }
    }

    /**
     * Toggle Ad
     */

    public function toggleAd(Request $request, $id)
    {
        //

        $fastVisit = new FastVisit();

        $this->validate($request, [
            'project_id' => 'required'
        ]);
        $project_id = $request->project_id;
        if (ProjectMembersController::userInProject($project_id)) {
            $fastVisit_sql = $fastVisit->where('id', $id);
            $data = $fastVisit->where('id', $id)->firstOrFail();
            $showAd = $data->show_ad;
            if ($showAd) {
                $showAd = false;
                $status = '关';
            } else {
                $showAd = true;
                $status = '开';
            }
            $fastVisit_sql->update([
                'show_ad' => $showAd
            ]);

            ProjectActivityController::save($project_id, '更改了 快捷访问 ' . $data->name . ' 的广告状态为 ' . $status . ' 。');

            return response()->json(['status' => 'success', 'message' => $showAd]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'You do not have permission to edit this.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (self::delete_fast_visit($id)) {
            return redirect()->back()->with('status', '访问入口 删除完成。');
        } else {
            return redirect()->back()->with('status', '访问入口 删除失败。');
        }
    }

    public static function delete_fast_visit($id)
    {
        $fastVisit = new FastVisit();
        $fastVisit_sql = $fastVisit->where('id', $id);
        $fastVisit_data = $fastVisit_sql->firstOrFail();

        $project_id = $fastVisit_data->project->id;
        if (ProjectMembersController::userInProject($project_id)) {
            ProjectActivityController::save($project_id, '删除了 快捷访问 ' . $fastVisit_data->name . ' 。');

            $fastVisit_sql->delete();
            return true;
        } else {
            return false;
        }
    }
}