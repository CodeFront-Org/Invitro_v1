<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NewAdminRegister extends Controller
{
    public function register(Request $request){
       //return $request;
        // Validate the form data
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6',
            'terms' => 'accepted',
        ]);

        // Check if validation passed
        if (!empty($validatedData)) {
            // Validation passed, continue with your logic
            // Access the validated data
           $firstname = $validatedData['firstname'];
            $lastname = $validatedData['lastname'];
            $phone = $validatedData['phone'];
            $email = $validatedData['email'];
            $password = $validatedData['password'];

            $contacts = substr($phone, -9); //Filter contacts to MPESA format
            $mpesa_contacts = '254'.$contacts;

            // Create the new user
            $user = User::create([
                'first_name' => $firstname,
                'last_name' => $lastname,
                'contacts' => $phone,
                'email' => $email,
                'password' => Hash::make($password),
                'role_type'=>'1'
            ]);

            if ($user) { //succsess registration
        //Assign role and permissions to a user
        $user=User::where('role_type',1)->where('email',$email)->first();
        $user->assignRole('admin');
        $permissions = Permission::all();
        $user->syncPermissions($permissions);
        // $user->givePermissionTo('permission-name');
        echo 'Success. Just go to login dash and continue if not redirected automatically';

            } else {//failed registration
                return '2';
            }
            // Redirect back with success message or return JSON response
            // ...
        } else {
            // Validation failed
            // Handle the validation errors
            return "3"; //failed validation
        }
    }
}