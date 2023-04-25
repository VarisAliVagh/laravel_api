<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $list = User::all()->toArray();
        if (count($list) > 0) {
            return response()->json([
                "message" => count($list) . " users found",
                "data" => $list,
                "status" => 1
            ]);
        } else {
            return response()->json([
                'message' => count($list) . " users found",
                "status" => 0
            ]);
        }
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
        $valid = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:5']
        ]);

        if ($valid->fails()) {
            return response()->json($valid->messages(), 400);
        } else {
            DB::beginTransaction();
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ];
            try {
                $user = User::create($data);
                DB::commit();
            } catch (\Exception $e) {
                pre($e->getMessage());
                // DB::rollBack();
                $user = null;
            }

            if ($user != null) {
                return response()->json([
                    'message' => 'user registered successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'internal server error'
                ], 500);
            }
        }
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
