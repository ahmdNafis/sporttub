<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    private $contact;
    public function __construct(Contact $c) {
        $this->contact = $c;
    }

    public function index()
    {
        $cont = $this->contact->get(['id', 'name', 'email', 'created_at'])->toArray();
        $filtered = $columns = [];
        foreach($cont as $in => $arr) {
            foreach($arr as $col => $val) {
                switch ($col) {
                    case 'created_at':
                        $filtered[$in]['posted_date'] = date('d/m/y', strtotime($val));
                        if(!in_array('posted_date', $columns)) array_push($columns, 'posted_date');
                        break;
                    
                    default:
                        $filtered[$in][$col] = $val;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                }
            }
        }
        array_push($columns, 'Action');
        return view('contact.index', ['data' => $filtered, 'columns' => $columns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $columns = Schema::getColumnListing('contact_us');
        $new = new Contact();
        foreach($data as $col => $val) {
            if(in_array($col, $columns) && !empty($val)) $new->$col = $val;
        }
        if($new->save()) return back()->with('success_status', 'Your Query has been posted successfully');
        return back()->with('fail_status', 'Your query couldn\'t be submitted. Please check again');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->contact->find($id)->toArray();
        return view('contact.show', ['data' => $data]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->contact->find($id);
        if($data->delete()) return redirect('/contact')->with('success_status', 'Contact Number: '.$id.' has been deleted successfully');
        return redirect('/contact')->with('fail_status', 'Contact Number: '.$id.' deletion failed');
    }
}
