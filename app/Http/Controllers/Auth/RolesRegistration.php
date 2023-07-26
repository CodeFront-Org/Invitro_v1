<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesRegistration extends Controller
{
    public function index(){

//Regestering Roles
$role1 = Role::create(['name' => 'admin']);
$role2 = Role::create(['name' => 'customer']);
$role3 = Role::create(['name' => 'staff']);
//$role4 = Role::create(['name' => 'tenant']);

//Registrering permissions
$permission1 = Permission::create(['name' => 'read']);
$permission2 = Permission::create(['name' => 'write']);
$permission3 = Permission::create(['name' => 'edit']);
$permission4 = Permission::create(['name' => 'manage']);
$permission5 = Permission::create(['name' => 'delete']);

//Checking success status
if(($role1 and $role2 and $role3) and ($permission1 and $permission2 and $permission3 and $permission4 and $permission5)){
echo 'Roles and Permissions registered Succesfully<br>';
//Assigning permissions to roles
//Getting Roles
$role1=Role::where('name','admin')->first();
$role2=Role::where('name','customer')->first();
$role3=Role::where('name','staff')->first();
//Getting permissions
$permission1 ='read';
$permission2 ='write';
$permission3 ='edit';
$permission4 = 'manage';
$permission5 ='delete';
//Assigning permissions to roles
$role1->givePermissionTo(Permission::all());  //Use this toAssign role to all also instead of sync
$role2->givePermissionTo([$permission1,$permission2]);
$role3->givePermissionTo([$permission1,$permission2,$permission3]);


echo "Succesfully assigned Permissions";

}else{
return "Error in Regestration of Roles and Permissions";
}
    }
}