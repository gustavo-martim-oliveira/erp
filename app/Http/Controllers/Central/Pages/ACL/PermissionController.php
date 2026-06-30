<?php

namespace App\Http\Controllers\Central\Pages\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Throwable;

class PermissionController extends Controller
{
    /**
     * Cria uma nova permissão.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name'),
            ],

            'alias' => [
                'required',
                'string',
                'max:255',
            ],

            'group' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        DB::beginTransaction();

        try {

            Permission::create([
                'name' => strtolower(trim($validated['name'])),
                'alias' => trim($validated['alias']),
                'group' => trim($validated['group']),
                'guard_name' => 'web',
            ]);

            DB::commit();

            return redirect()
                ->route('central.acl.index')
                ->with('success', 'Permissão criada com sucesso.');

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível criar a permissão.');
        }
    }

    /**
     * Atualiza uma permissão.
     */
    public function update(
        Request $request,
        Permission $permission
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permission->id),
            ],

            'alias' => [
                'required',
                'string',
                'max:255',
            ],

            'group' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        DB::beginTransaction();

        try {

            $permission->update([
                'name' => strtolower(trim($validated['name'])),
                'alias' => trim($validated['alias']),
                'group' => trim($validated['group']),
            ]);

            app()->make(\Spatie\Permission\PermissionRegistrar::class)
                ->forgetCachedPermissions();

            DB::commit();

            return redirect()
                ->route('central.acl.index')
                ->with('success', 'Permissão atualizada com sucesso.');

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível atualizar a permissão.');
        }
    }

    /**
     * Remove uma permissão.
     */
    public function destroy(
        Permission $permission
    ): RedirectResponse {

        DB::beginTransaction();

        try {

            $permission->roles()->detach();

            $permission->delete();

            app()->make(\Spatie\Permission\PermissionRegistrar::class)
                ->forgetCachedPermissions();

            DB::commit();

            return redirect()
                ->route('central.acl.index')
                ->with('success', 'Permissão removida com sucesso.');

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with('error', 'Não foi possível remover a permissão.');
        }
    }
}