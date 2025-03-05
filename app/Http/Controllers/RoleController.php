<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // Display a listing of the roles
    public function index()
    {
        $roles = Role::all();
        return view('dashboard.admin.roles', compact('roles'));
    }

    // Add a new role
    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Create a new role instance
        $role = new Role();
        $role->name = $request->name;

        // Attempt to save the role and check success
        if ($role->save()) {
            return response()->json(['code' => 1, 'msg' => 'Role Added Successfully', 'redirect' => route('roles.index')]); 
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    // Get details of a specific role
    public function getRoleDetails(Request $request)
    {
        $role_id = $request->role_id;
        $roleDetails = Role::find($role_id);
        return response()->json(['details' => $roleDetails]);
    }

    // Update role details
    public function updateRoleDetails(Request $request)
    {
        $role_id = $request->role_id;
        $role = Role::find($role_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $role->name = $request->name;

        if ($role->save()) {
            return response()->json(['code' => 1, 'msg' => 'Role Updated Successfully', 'redirect' => route('roles.index')]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    // Delete a role
    public function deleteRole(Request $request)
    {
        $role_id = $request->role_id;
        $role = Role::find($role_id);

        if ($role->delete()) {
            return response()->json(['code' => 1, 'msg' => 'Role Deleted Successfully', 'redirect' => route('roles.index')]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
