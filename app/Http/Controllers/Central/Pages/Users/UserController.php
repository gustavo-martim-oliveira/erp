<?php

namespace App\Http\Controllers\Central\Pages\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Central\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Lista de usuários.
     */
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));

        $users = User::query()
            ->with('roles')
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");

                });

            })
            ->orderBy('name')
            ->paginate(15);

        $roles = Role::with('permissions')->orderBy('name')->get();

        $userResources = UserResource::collection($users);
        $permissions = Permission::orderBy('name')->get();

        return view('central.pages.users.index', [
            'users'  => $users,
            'userResources' => $userResources,
            'roles'  => $roles,
            'search' => $search,
            'page'   => 'users',
            'title'  => 'Usuários',
            'permissions' => $permissions,
        ]);
    }

    /**
     * Cadastra um usuário.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', 'min:6'],
            'role'                  => ['required', 'exists:roles,name'],
            'is_active'             => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->assignRole($request->role);

        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }else {
            $user->syncPermissions([]);
        }

        return redirect()
            ->route('central.users.index')
            ->with('success', 'Usuário cadastrado com sucesso.');
    }

    public function edit(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'roles' => $user->roles,
            'role_permissions' => $user->getPermissionsViaRoles()->pluck('name'),
            'direct_permissions' => $user->getDirectPermissions()->pluck('name'),
        ]);
    }

    /**
     * Atualiza um usuário.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'role'      => ['required', 'exists:roles,name'],
            'password'  => ['nullable', 'confirmed', 'min:6'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }else {
            $user->syncPermissions([]);
        }

        return redirect()
            ->route('central.users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove um usuário.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {

            return redirect()
                ->route('central.users.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');

        }

        $user->delete();

        return redirect()
            ->route('central.users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }
}