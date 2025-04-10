<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function changePassword(ChangePasswordRequest $request){
        $user = Auth::user();
        $user = User::find($user->id);
        $user->password = Hash::make($request->input("password"));
        $user->change_password = false;
        $user->save();

        return redirect($request->server("HTTP_REFERER"))->with("message","Password successfully modified!");
    }
}
