<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::paginate(20);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        $roles = Role::where('name', '!=', 'super user')->orderBy('name', 'ASC')->get();
        return view('users.form', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect(route('users.index'))
            ->with('status', 'success')
            ->with('message', 'Berhasil Menyimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'super user')->orderBy('name', 'ASC')->get();
        return view('users.form', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable'
        ], [
            
        ],
        [
            'role_id' => 'Jabatan'
        ]);

        if(isset($validated['password']) && !empty($validated['password']))
        {
            $validated['password'] = Hash::make($validated['password']);
        }else {
            unset($validated['password']);
        }
        
        $user->update($validated);

        return back()
            ->with('status', 'success')
            ->with('message', 'Berhasil Menyimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->problems()->count())
        {
            return back()
                ->with('status', 'warning')
                ->with('message', 'Tidak dapat menghapus user sebelum menghapus masalah');

        }

        try {
            $user->delete();

            return back()
                ->with('status', 'success')
                ->with('message', 'Berhasil Menghapus');

        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
