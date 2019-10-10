<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private $user;
    public function __construct(User $u)
    {
        $this->user = $u;
    }
    public function show($user_id) {
        $user = $this->user->find($user_id)->toArray();
        $ignore_arr = ['updated_at', 'email_verified_at', 'social_id', 'user_status', 'nickname'];
        $filtered = [];
        foreach($user as $key => $val) {
            if(!in_array($key, $ignore_arr)) $filtered[$key] = $val;
        }
        return view('profile.show', ['user' => $filtered]);
    }

    public function edit($user_id) {
        $user = $this->user->find($user_id);
        return view('profile.edit', ['properties' => $user]);
    }

    public function update(Request $request) {
        $id = $request->input('user_id');
        $data = $request->all();
        $user = $this->user->find($id);
        $ignore = ['user_id', '_method', '_token', 'password'];
        foreach($data as $col => $val) {
            if(!in_array($col, $ignore) && !empty($val)) $user->$col = $val;
            if($col == 'password' && !empty($val)) $user->$col = Hash::make($val);
        }
        if($user->save()) return back()->with('success_status', 'Your data has been updated successfully');
        else return back()->with('fail_status', 'Your data couldn\'t updated successfully');
    }
}
