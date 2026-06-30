<div>

    <form method="GET" action="{{ route('central.acl.index') }}" class="mb-4 flex items-center justify-between gap-4">

        <div class="flex w-full max-w-md gap-2">

            <input
                type="text"
                name="role_search"
                value="{{ request('role_search') }}"
                placeholder="Buscar papel..."
                class="w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-700 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
            >

            <input type="hidden" name="tab" value="roles">

            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
            >
                Buscar
            </button>

            @if(request()->filled('role_search'))
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
            onclick="openRoleCreateModal()"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
        >
            + Novo Papel
        </button>

    </form>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">

        <table class="w-full">

            <thead class="bg-gray-50 dark:bg-gray-800">

                <tr>

                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300">
                        Nome
                    </th>

                    <th class="px-5 py-3 text-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                        Permissões
                    </th>

                    <th class="px-5 py-3 text-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                        Usuários
                    </th>

                    <th class="px-5 py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-300">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($roles as $role)

                    <tr class="border-t border-gray-200 dark:border-gray-700">

                        <td class="px-5 py-4">

                            <div class="font-medium text-gray-800 dark:text-white">
                                {{ $role->name }}
                            </div>

                        </td>

                        <td class="px-5 py-4 text-center text-gray-600 dark:text-gray-300">
                            {{ $role->permissions->count() }}
                        </td>

                        <td class="px-5 py-4 text-center text-gray-600 dark:text-gray-300">
                            {{ $role->users_count }}
                        </td>

                        <td class="px-5 py-4">

                            <div class="flex justify-end gap-2">

                                @if($role->name !== 'Super Admin')

                                    <button
                                        type="button"
                                        onclick='editRole(@json($role))'
                                        class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        type="button"
                                        onclick="deleteRole({{ $role->id }})"
                                        class="rounded-lg bg-[#FF0000] px-3 py-1.5 text-xs font-medium text-white hover:opacity-90"
                                    >
                                        Excluir
                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="px-5 py-8 text-center text-gray-500 dark:text-gray-400"
                        >
                            Nenhum papel encontrado.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-5">
        {{ $roles->appends(request()->query())->links() }}
    </div>

</div>