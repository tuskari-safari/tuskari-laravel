<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    public function userlist(Request $request)
    {
        try {
            $users = User::query();
            if ($request->name) {
                $users = $users->WhereRaw(
                    "concat(first_name,' ', last_name) like '%" . trim(addslashes($request->name)) . "%' "
                );
            }

            if ($request->email) {
                $users = $users->where('email', 'like', '%' . trim($request->email) . '%');
            }

            if ($request->phone) {
                $users = $users->where('phone', 'like', '%' . trim($request->phone) . '%');
            }
            if (isset($request->active)) {
                $users = $users->where('active', $request->active);
            }

            if ($request->fieldName && $request->shortBy) {
                $users->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;
            $users = $users->latest()->role('TRAVELLER')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/user/List', ['users' => $users]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function createUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'first_name' => 'required|max:40|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|max:40|regex:/^[a-zA-Z\s]+$/',
                'email' => ['required', 'email', 'regex:/(.+)@(.+)\.(.+)/i', 'max:255', Rule::unique('users')],
                'password' => 'required|min:6|max:20',
                'phone' => ['required', 'digits_between:10,15', 'numeric', Rule::unique('users')],
                'dob' => 'required',
                'profile_photo' => 'required|mimes:jpeg,png,jpg|max:10240',
                'status' => 'required',
            ]);

            try {
                $user = new User;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->password = $request->password;
                $user->phone = $request->phone;
                $user->dob = date('Y-m-d', strtotime($request->dob));
                $user->active = $request->status ?? 1;
                if ($request->hasFile('profile_photo')) {
                    $file = $request->file('profile_photo');
                    $path = 'profile_photo';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $user->profile_photo_path = $final_image_url;
                }
                $user->save();
                $user->assignRole('TRAVELLER');

                session()->flash('success', 'User successfully created');
                return redirect()->route('admin.users');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/user/CreateEdit');
    }

    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        $prev_picture = $user->profile_photo_path;
        if (request()->isMethod('post')) {
            $credentials = $request->validate([
                'first_name' => 'required|max:40|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|max:40|regex:/^[a-zA-Z\s]+$/',
                'email' =>  'required|max:255|regex:/(.+)@(.+)\.(.+)/i|email|unique:users,email,' . $user->id,
                'phone' => 'required|digits_between:10,15|nullable|unique:users,phone,' . $user->id,
                'dob' => 'required',
                'status' => 'required',
                'profile_photo' => $request->hasFile('profile_photo') ? 'mimes:jpeg,png,jpg|max:10240' : '',
            ]);
            
            try {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->country_code = $request->country_code;
                $user->dob = date('Y-m-d', strtotime($request->dob));
                $user->active = $request->status ?? 1;

                if ($request->hasFile('profile_photo')) {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }

                    $file = $request->file('profile_photo');
                    $path = 'profile_photo';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $user->profile_photo_path = $final_image_url;
                }
                $user->save();

                session()->flash('success', 'User successfully updated');
                return redirect()->route('admin.users');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/user/CreateEdit', compact('user'));
    }

    public function deleteUser($id)
    {
        try {
            $user = User::find($id);
            if (isset($user->profile_photo_path) && file_exists($user->profile_photo_path)) {
                @unlink($user->profile_photo_path);
            }
            User::where('id', $id)->delete();

            session()->flash('success', 'User successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeUserStatus(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->active = !$user->active;
            $user->save();
            session()->flash('success', 'User status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
