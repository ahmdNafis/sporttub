<?php

namespace App\Http\Controllers;

use App\Privacy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $privy;
    private $state;
    public function __construct(Privacy $p)
    {
        $this->privy = $p;
        $this->state = ['Activate', 'Deactivate'];
    }

    public function index() {
        $private = $this->privy->first();
        return view('privacy.index', ['data' => $private]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('privacy.new', ['status' => $this->state]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $content = $request->input('content_privacy');
        $state = $request->input('privacy_status');

        $private = new Privacy();
        $private->content_privacy = $content;
        $private->privacy_status = $state;

        if($private->save()) return redirect('/privacy')->with('success_status', 'Privacy Policy inserted successfully');
        else return back()->with('fail_status', 'Privacy Policy creation failed');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $priv = $this->privy->find($id);
        return view('privacy.edit', ['data' => $priv]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('policy_id');
        $priv = $this->privy->find($id);
        $data = $request->all();
        $ignore = ['policy_id', '_method', '_token'];
        foreach ($data as $col => $value) {
            if(!in_array($col, $ignore) && !empty($value)) $priv->$col = $value;
        }
        if($priv->save()) return redirect('/privacy')->with('success_status', 'Privacy Policy updated successfully');
        else return back()->with('fail_status', 'Privacy Policy update failed');
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
