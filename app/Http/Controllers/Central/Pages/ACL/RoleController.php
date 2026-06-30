<?php

namespace App\Http\Controllers\Central\Pages\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends Controller
{
    /**
     * Armazena um novo Regra.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name'),
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);

        DB::beginTransaction();

        try {

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);

            $permissions = Permission::whereIn(
                'id',
                $validated['permissions'] ?? []
            )->pluck('name');

            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('central.acl.index')
                ->with('success', 'Regra criado com sucesso.');

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível criar o Regra.');
        }
    }

    /**
     * Atualiza um Regra.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);

        DB::beginTransaction();

        try {

            $role->update([
                'name' => $validated['name'],
            ]);

            $permissions = Permission::whereIn(
                'id',
                $validated['permissions'] ?? []
            )->pluck('name');

            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('central.acl.index')
                ->with('success', 'Regra atualizado com sucesso.');

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar o Regra.');
        }
    }

    /**
     * Remove um Regra.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'Super Admin') {
            return back()->with(
                'error',
                'O Regra Super Admin não pode ser removido.'
            );
        }

        DB::beginTransaction();

        try {

            $role->delete();

            DB::commit();

            return redirect()
                ->route('central.acl.index')
                ->with('success', 'Regra removida com sucesso.');

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with('error', 'Não foi possível remover a Regra.');
        }
    }
}