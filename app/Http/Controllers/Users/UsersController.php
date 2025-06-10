<?php

namespace App\Http\Controllers\Users;

use App\Exports\LogExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\EditUserRequest;
use App\Http\Requests\Users\MyProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $user->password = Hash::make($request->input("password"));
        $user->change_password = false;
        $user->save();

        Utils::createLog(
            "The user has changed his password",
            "users.change-password",
            "update"
        );

        return redirect($request->server("HTTP_REFERER"))->with("message", "Password successfully modified!");
    }

    public function showProfileForm()
    {
        $user = Auth::user();

        Utils::createLog(
            "The user has entered profile form",
            "users.profile",
            "show"
        );
        return view('users.myProfile', [
            'user' => $user
        ]);
    }

    public function updateProfile(MyProfileRequest $request)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        if ($request->has("password")) {
            $user->password = Hash::make($request->input("password"));
        }
        if ($request->has("image")) {
            if (isset($user->image)) {
                Storage::disk('public')->delete('users/' . $user->image);
            }

            $file = $request->file("image");
            $file_name =  time() . "_" . $file->getClientOriginalName();
            $file->move(public_path("storage/users"), $file_name);
            $user->image = $file_name;
        }
        $user->save();

        Utils::createLog(
            "The user has updated his profile",
            "users.profile",
            "update"
        );

        return redirect(route('users.profile'))->with("message", "Profile successfully modified!");
    }


    public function show()
    {
        Utils::createLog(
            "The user entered the users list.",
            "users.users",
            "show"
        );
        
        return view('users.show');
    }
    public function datatableAjax(Request $request)
    {
        $entry_user = Auth::user();

        $users = User::select("users.*");

        if ($entry_user->role == "admin") {
            $users->whereIn("role", ["admin", "agent", "supervisor"]);
        }

        $users->where("id", "<>", $entry_user->id);


        if ($request->has('search') && $request->input('search')['value']) {
            $searchTxt = $request->input('search')['value'];
            $users->where(function ($query) use ($searchTxt) {
                $query->where("users.name", "like", "%{$searchTxt}%")
                    ->orWhere("users.email", "like", "%{$searchTxt}%")
                    ->orWhere("users.role", "like", "%{$searchTxt}%");
            });
        }
        if ($request->has('order')) {
            $column = $request->input('order')[0]['column'];
            $direction = $request->input('order')[0]['dir'];
            switch ($column) {
                case '0':
                    $users->orderBy("users.name", $direction);
                    break;
                case '1':
                    $users->orderBy("users.email", $direction);
                    break;
                case '2':
                    $users->orderBy("users.role", $direction);
                    break;
                case '3':
                    $users->orderBy("users.status", $direction);
                    break;
            }
        }

        $totalRecords = $users->count();
        $users = $users->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $filteredRecords = array();

        foreach ($users as $user) {
            $filteredRecord = array();
            $filteredRecord["name"] = $user->name;
            $filteredRecord["email"] = $user->email;
            $filteredRecord["role"] = ucfirst($user->role);
            $filteredRecord["status"]["number"] = $user->status;
            $filteredRecord["status"]["text"] = $user->txt_status;
            $filteredRecord["actions"]["edit"] = route('users.update', ['id' => $user->id]);
            $filteredRecord["actions"]["log"] = route('users.log', ['id' => $user->id]);

            array_push($filteredRecords, $filteredRecord);
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $filteredRecords
        ]);
    }

    public function showCreateForm()
    {
        $entry_user = Auth::user();
        $available_roles = [];
        if ($entry_user->role == "admin") {
            $available_roles = ["supervisor", "admin"];
        }
        if ($entry_user->role == "superadmin") {
            $available_roles = ["supervisor", "admin", "superadmin"];
        }

        Utils::createLog(
            "The user has entered the form to create users",
            "users.users",
            "show"
        );

        return view('users.create', [
            'roles' => $available_roles
        ]);
    }
    public function create(CreateUserRequest $request)
    {
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->role = $request->input("role");
        $user->status = $request->input("status");

        if ($request->has("image")) {
            if (isset($user->image)) {
                Storage::disk('public')->delete('users/' . $user->image);
            }

            $file = $request->file("image");
            $file_name =  time() . "_" . $file->getClientOriginalName();
            $file->move(public_path("storage/users"), $file_name);
            $user->image = $file_name;
        }
        $user->save();

        Utils::createLog(
            "The user has created a new user with ID: ".$user->id,
            "users.users",
            "create"
        );
        return redirect(route('users.show'))->with('message', 'User created successfully');
    }
    public function showUpdateForm($id)
    {
        $user = User::find($id);
        $entry_user = Auth::user();
        $available_roles = [];
        if ($entry_user->role == "admin") {
            $available_roles = ["supervisor", "admin"];
        }
        if ($entry_user->role == "superadmin") {
            $available_roles = ["supervisor", "admin", "superadmin"];
        }
        if (!in_array($user->role, $available_roles)) {
            return redirect(route('users.show'))->with('error', 'You cannot modify this user');
        }

        Utils::createLog(
            "The user has entered the form to update products with ID: ".$user->id,
            "users.users",
            "show"
        );



        return view('users.update', [
            'user' => $user,
            'roles' => $available_roles
        ]);
    }
    public function update($id, EditUserRequest $request)
    {
        $user = User::find($id);
        $entry_user = Auth::user();
        $available_roles = [];
        if ($entry_user->role == "admin") {
            $available_roles = ["supervisor", "admin"];
        }
        if ($entry_user->role == "superadmin") {
            $available_roles = ["supervisor", "admin", "superadmin"];
        }
        
        if (!in_array($user->role, $available_roles)) {
            return redirect(route('users.show'))->with('error', 'You cannot modify this user');
        }

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->status = $request->input("status");
        $user->change_password = $request->input("change_password");
        if ($request->has("password")) {
            $user->password = Hash::make($request->input("password"));
        }
        if ($request->has("image")) {
            if (isset($user->image)) {
                Storage::disk('public')->delete('users/' . $user->image);
            }

            $file = $request->file("image");
            $file_name =  time() . "_" . $file->getClientOriginalName();
            $file->move(public_path("storage/users"), $file_name);
            $user->image = $file_name;
        }
        $user->save();

        Utils::createLog(
            "The user has modified the user with ID: ".$user->id,
            "users.users",
            "update"
        );

        return redirect(route('users.show'))->with('message', 'User updated successfully');
    }

    public function showLogForm($id){

        Utils::createLog(
            "The user has entered the user log to ID: ".$id,
            "users.log",
            "show"
        );

        return view('users.log', [
            'userID' => $id
        ]);
    }

    public function downloadLog($id, Request $request){
        
        Utils::createLog(
            "The user has downloaded the Log report for user ID: ".$id,
            "users.log",
            "create"
        );

        return Excel::download(new LogExport($id, $request->input("start_date"), $request->input("end_date")), 'Log Report '.$id.'.xlsx');
    }
    
}
