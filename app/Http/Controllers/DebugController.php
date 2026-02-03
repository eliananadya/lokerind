<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DebugController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function checkRole()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ]);
        }

        $user = Auth::user();

        return response()->json([
            '✅ User Info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            '✅ Role Info' => [
                'id_roles' => $user->id_roles ??  'NULL',
                'role_object_exists' => $user->role ?  'YES' : 'NO',
                'role_name' => $user->role->name ?? 'NULL',
                'role_description' => $user->role->description ?? 'NULL',
            ],
            '✅ Relationships' => [
                'has_role_relation' => method_exists($user, 'role') ? 'YES' : 'NO',
                'role_loaded' => $user->relationLoaded('role') ? 'YES' : 'NO',
            ],
            '✅ Helper Methods' => [
                'isCompany()' => method_exists($user, 'isCompany') ? ($user->isCompany() ? 'TRUE' : 'FALSE') : 'Method not exists',
                'isCandidate()' => method_exists($user, 'isCandidate') ? ($user->isCandidate() ? 'TRUE' : 'FALSE') : 'Method not exists',
                'hasRole()' => method_exists($user, 'hasRole') ?  'Method exists' : 'Method not exists',
            ],
            '✅ Full User Data' => $user->toArray(),
            '✅ Database Check' => [
                'users_table_id_roles' => \DB::table('users')->where('id', $user->id)->value('id_roles'),
                'roles_table_exists' => \Schema::hasTable('roles') ? 'YES' : 'NO',
                'role_from_db' => \DB::table('roles')->where('id', $user->id_roles)->first(),
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
