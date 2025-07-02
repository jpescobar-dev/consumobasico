<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        $validated['password'] = bcrypt($validated['password']);
    
        $user = User::create($validated);
    
        if ($request->has('roles')) {
            $user->assignRole($request->roles); // array de nombres
        }
    
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }
    

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }



    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

   public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    if ($request->has('roles')) {
        $user->syncRoles($request->roles); // array de nombres
    }

    return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
}


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}
