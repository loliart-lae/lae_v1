<?php

namespace App\Http\Controllers\api\v1;

use App\Models\FastVisit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FastVisitDomain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectMembersController;

class FastVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fastVisits = FastVisit::with('server')->whereHas('member', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('project_id')->get();

        return response()->json([
            'status' => 1,
            'data' => $fastVisits
        ]);
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
    public function store(Request $request, FastVisit $fastVisit, FastVisitDomain $domain)
    {
        // I'm lazy.

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
                return response()->json([
                    'status' => 0,
                    'msg' => 'domain not found.'
                ]);
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

            return response()->json([
                'status' => 1,
                'data' => [
                    'id' => $fastVisit->id,
                    'name' => $request->name,
                    'slug' => $str,
                    'uri' => $request->uri
                ]
            ]);
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
        if (\App\Http\Controllers\FastVisitController::delete_fast_visit($id)) {
            return response()->json([
                'status' => 1,
                'data' => $id
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'FastVisit delete failed.'
            ]);
        }
    }
}
