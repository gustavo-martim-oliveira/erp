<div id="roleEditModal" class="hidden fixed flex z-[9999999] inset-0 bg-black/50 items-center justify-center">
    <div class="bg-white w-[500px] p-6 rounded">

        <h2 class="text-lg font-bold mb-4">Editar Papel</h2>

        <form id="roleEditForm" method="POST">
            @csrf
            @method('PUT')

            <input
                type="text"
                name="name"
                id="edit_role_name"
                class="w-full border p-2 rounded mb-4"
            >

            <div class="max-h-64 overflow-auto border p-2 rounded">
                @foreach($permissionGroups as $group => $items)
                    <div class="mb-2 font-bold">{{ $group }}</div>

                    @foreach($items as $permission)
                        <label class="block text-sm">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                class="role-permission-checkbox"
                            >
                            {{ $permission->alias }}
                        </label>
                    @endforeach
                @endforeach
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeRoleEditModal()">Cancelar</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Atualizar</button>
            </div>

        </form>

    </div>
</div>