<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display list of users (Admin only)
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->latest()->get();
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show create user form (Admin only)
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        // Only show manager role (admins created via seeder)
        $roles = Role::where('name', 'manager')->get();
        
        return view('admin.users.create', compact('roles'));
    }
    
    /**
     * Store new user (Admin only - create Manager)
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Assign manager role
            $user->assignRole('manager');
            
            return $user;
        });
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Manager created successfully');
    }
    
    /**
     * Delete user (Admin only - cannot delete self)
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Prevent self-deletion (also in Policy but double-check)
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself');
        }
        
        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
