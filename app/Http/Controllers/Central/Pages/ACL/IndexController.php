<?php

namespace App\Http\Controllers\Central\Pages\ACL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class IndexController extends Controller
{
    /**
     * Exibe a tela principal do módulo ACL.
     */
    public function __invoke(Request $request)
    {
        $roleSearch = trim($request->get('role_search', ''));
        $permissionSearch = trim($request->get('permission_search', ''));

        $roles = Role::query()
            ->with('permissions')
            ->withCount('users')
            ->when($roleSearch !== '', function ($query) use ($roleSearch) {
                $query->where('name', 'like', "%{$roleSearch}%");
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'roles');

        $permissions = Permission::query()
            ->when($permissionSearch !== '', function ($query) use ($permissionSearch) {
                $query->where('name', 'like', "%{$permissionSearch}%");
            })
            ->orderBy('name')
            ->paginate(20, ['*'], 'permissions');

        $permissionGroups = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function (Permission $permission) {
                if(!empty($permission->group)) {
                    return ucfirst($permission->group);
                }else if (!str_contains($permission->name, '.')) {
                    return 'Outros';
                }

                return ucfirst(explode('.', $permission->name)[0]);
            });

        $tab = $request->get('tab', 'roles');

        return view('central.pages.acl.index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'permissionGroups' => $permissionGroups,
            'roleSearch' => $roleSearch,
            'permissionSearch' => $permissionSearch,
            'page' => 'acl',
            'title' => 'Controle de Acesso',
            'tab' => $tab,
        ]);
    }
}