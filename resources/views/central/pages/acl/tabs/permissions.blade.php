<div>

    <form method="GET" action="{{ route('central.acl.index') }}" class="mb-4 flex items-center justify-between gap-4">

        <div class="flex w-full max-w-md gap-2">

            <input
                type="text"
                name="permission_search"
                value="{{ request('permission_search') }}"
                placeholder="Buscar permissão..."
                class="w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-700 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >

            <input type="hidden" name="tab" value="permissions">

            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
            >
                Buscar
            </button>

            @if(request()->filled('permission_search'))
                <a
                    href="{{ route('central.acl.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800 dark:text-white"
                >
                    Limpar
                </a>
            @endif

        </div>

        <button
            type="button"
            onclick="openPermissionCreateModal()"
            class="rounded bg-blue-600 px-4 py-2 text-white text-sm"
        >
            + Nova Permissão
        </button>
    </form>

    <div class="overflow-x-auto rounded border border-gray-200 dark:border-gray-700 dark:bg-gray-800">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-left text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th class="p-3">Alias</th>
                    <th class="p-3">Nome técnico</th>
                    <th class="p-3">Grupo</th>
                    <th class="p-3 text-end">Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach($permissions as $permission)
                    <tr class="border-t dark:text-gray-300 dark:border-gray-700">
                        <td class="p-3 font-medium">
                            {{ $permission->alias }}
                        </td>

                        <td class="p-3 text-gray-500">
                            {{ $permission->name }}
                        </td>

                        <td class="p-3">
                            <span class="rounded bg-gray-100 px-2 py-1 text-xs dark:bg-gray-700 dark:text-gray-300">
                                {{ $permission->group }}
                            </span>
                        </td>

                        <td class="p-3 flex gap-2 text-end items-center justify-end">
                            <button
                                class="text-blue-600"
                                onclick='editPermission(@json($permission))'
                            >
                                Editar
                            </button>

                            <button
                                class="text-red-600"
                                onclick="deletePermission({{ $permission->id }})"
                            >
                                Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $permissions->links() }}
    </div>

</div>