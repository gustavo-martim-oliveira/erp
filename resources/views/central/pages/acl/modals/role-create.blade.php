<div id="roleCreateModal" class="hidden fixed flex z-[9999999] inset-0 bg-black/50 items-center justify-center">
    <div class="bg-white w-[500px] p-6 rounded">

        <h2 class="text-lg font-bold mb-4">Novo Papel</h2>

        <form method="POST" action="{{ route('central.acl.roles.store') }}">
            @csrf

            <input
                type="text"
                name="name"
                placeholder="Nome do papel"
                class="w-full border p-2 rounded mb-4"
            >

            <div class="max-h-64 overflow-auto border p-2 rounded">
                @foreach($permissionGroups as $group => $items)
                    <div class="mb-2 font-bold">{{ $group }}</div>

                    @foreach($items as $permission)
                        <label class="block text-sm">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                            {{ $permission->alias }}
                        </label>
                    @endforeach
                @endforeach
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeRoleCreateModal()">Cancelar</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
            </div>

        </form>

    </div>
</div>