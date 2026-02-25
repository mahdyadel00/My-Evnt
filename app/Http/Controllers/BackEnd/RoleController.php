<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Exception;

/**
 * Role Controller
 * 
 * Manages roles and permissions in the system
 */
class RoleController extends Controller
{
    /**
     * Display a listing of roles
     *
     * @return View
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('backend.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     *
     * @return View
     */
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('backend.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255|unique:roles,name',
            'permission'        => 'required|array|min:1',
            'permission.*'      => 'integer|exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();

            // Create role
            $role = Role::create(['name' => $validated['name']]);
            
            // Get permission names by IDs
            $permissions = Permission::whereIn('id', $validated['permission'])->pluck('name')->toArray();
            
            // Sync permissions using permission names
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role created successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating role', [
                'message' => $e->getMessage(),
                'role_name' => $validated['name'] ?? null
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create role. Please try again.');
        }
    }

    /**
     * Show the form for editing a role
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('backend.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update a role
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permission' => 'required|array|min:1',
            'permission.*' => 'integer|exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);
            
            // Update role name
            $role->update(['name' => $validated['name']]);
            
            // Get permission names by IDs
            $permissions = Permission::whereIn('id', $validated['permission'])->pluck('name')->toArray();
            
            // Sync permissions using permission names
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role updated successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating role', [
                'message' => $e->getMessage(),
                'role_id' => $id
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update role. Please try again.');
        }
    }

    /**
     * Delete a role
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $role = Role::findOrFail($id);

            // Prevent deletion of super admin role
            if (in_array($role->name, ['super-admin', 'admin'])) {
                return redirect()
                    ->route('admin.roles.index')
                    ->with('error', 'Cannot delete protected role: ' . $role->name);
            }

            // Check if role is assigned to any users
            if ($role->users()->count() > 0) {
                return redirect()
                    ->route('admin.roles.index')
                    ->with('error', 'Cannot delete role. It is assigned to ' . $role->users()->count() . ' user(s)');
            }

            $role->delete();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role deleted successfully');
                
        } catch (Exception $e) {
            Log::error('Error deleting role', [
                'message' => $e->getMessage(),
                'role_id' => $id
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete role. Please try again.');
        }
    }
}
