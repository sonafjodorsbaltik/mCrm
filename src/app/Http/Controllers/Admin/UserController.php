<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Controller for user management in admin panel.
 * 
 * Handles listing, creating, and deleting manager users.
 * Accessible only by admin role.
 */
class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display list of users (Admin only)
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->latest()->get();
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show create user form (Admin only)
     */
    public function create(): View
    {
        $this->authorize('create', User::class);
        
        // Only show manager role (admins created via seeder)
        $roles = Role::where('name', 'manager')->get();
        
        return view('admin.users.create', compact('roles'));
    }
    
    /**
     * Store new user (Admin only - create Manager)
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validated();
        
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
    public function destroy(User $user): RedirectResponse
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
