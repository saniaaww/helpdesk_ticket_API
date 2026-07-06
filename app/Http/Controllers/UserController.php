<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // =========================
    // GET /api/users/{id}
    // =========================
    public function show($id)
    {
        return response()->json(
            User::findOrFail($id)
        );
    }

    // =========================
    // PUT /api/users/{id}
    // =========================
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'role' => 'required',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    $user->save();

    return response()->json([
        'success' => true,
        'data' => $user,
    ]);
}

    // =========================
    // GET /api/helpdesk
    // =========================
    public function getHelpdesk()
    {
        return response()->json(

            User::where('role', 'helpdesk')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get()

        );
    }

    public function helpdeskUsers()
    {
        return User::where('role', 'helpdesk')
            ->select('id', 'name', 'email')
            ->get();
    }

    public function index()
    {
        return response()->json(

            User::orderBy('role')
                ->orderBy('name')
                ->get()

        );
    }

    public function store(Request $request)
    {

        $request->validate([

            'name'=>'required',

            'email'=>'required|email|unique:users,email',

            'password'=>'required|min:6',

            'role'=>'required'

        ]);

        $user = User::create([

            'name'=>$request->name,

            'email'=>$request->email,

            'password'=>bcrypt($request->password),

            'role'=>$request->role,

        ]);

        return response()->json([

            'success'=>true,

            'data'=>$user

        ]);

    }


    public function destroy($id)
    {

        User::findOrFail($id)->delete();

        return response()->json([

            'success'=>true

        ]);

    }
}