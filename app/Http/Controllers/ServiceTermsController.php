<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class ServiceTermsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $serve;
    private $state;
    public function __construct(Service $s) {
        $this->serve = $s;
        $this->state = ['Activate', 'Deactivate'];
    }

    public function index()
    {
        $service = $this->serve->first();
        return view('service.index', ['data' => $service]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('service.new', ['status' => $this->state]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $content = $request->input('content_service');
        $state = $request->input('service_status');

        $serve = new Service();
        $serve->content_service = $content;
        $serve->service_status = $state;

        if($serve->save()) return redirect('/service')->with('success_status', 'Terms of Service inserted successfully');
        else return back()->with('fail_status', 'Terms of Service creation failed');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->serve->find($id)->first();
        return view('service.edit', ['data' => $data]);
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
        $id = $request->input('service_id');
        $serve = $this->serve->find($id);
        $data = $request->all();
        $ignore = ['service_id', '_method', '_token'];
        foreach ($data as $col => $value) {
            if(!in_array($col, $ignore) && !empty($value)) $serve->$col = $value;
        }
        if($serve->save()) return redirect('/service')->with('success_status', 'Terms of Service updated successfully');
        else return back()->with('fail_status', 'Terms of Service update failed');
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
