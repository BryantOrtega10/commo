<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\MyProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function showProfileForm(){
        $user = Auth::user();

        return view('users.myProfile',[
            'user' => $user
        ]);
    }
    
    public function updateProfile(MyProfileRequest $request){
        $user = Auth::user();
        $user = User::find($user->id);
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        if($request->has("password")){
            $user->password = Hash::make($request->input("password"));
        }
        if($request->has("image")){
            if(isset($user->image)){
                Storage::disk('public')->delete('users/'.$user->image);
            }

            $file = $request->file("image");
            $file_name =  time() . "_" . $file->getClientOriginalName();
            $file->move(public_path("storage/users"), $file_name);
            $user->image = $file_name;
        }
        $user->save();

        return redirect(route('users.profile'))->with("message","Profile successfully modified!");

    }
}
