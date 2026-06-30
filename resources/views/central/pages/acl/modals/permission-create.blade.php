<div id="permissionCreateModal" class="hidden fixed flex z-[9999999] inset-0 bg-black/50 items-center justify-center">
    <div class="bg-white w-[500px] p-6 rounded shadow-lg dark:bg-gray-800 dark:text-white space-y-4">

        <h2 class="text-lg font-bold mb-4">
            Nova Permissão
        </h2>

        <form method="POST" action="{{ route('central.acl.permissions.store') }}">
            @csrf

            {{-- NOME TÉCNICO --}}
            <label class="block text-sm mb-1">Nome técnico</label>
            <input
                type="text"
                name="name"
                placeholder="users.create"
                class="w-full border p-2 rounded mb-4"
                required
            >

            {{-- ALIAS --}}
            <label class="block text-sm mb-1">Alias (título)</label>
            <input
                type="text"
                name="alias"
                placeholder="Criar Usuário"
                class="w-full border p-2 rounded mb-4"
                required
            >

            {{-- GRUPO --}}
            <label class="block text-sm mb-1">Grupo</label>
            <input
                type="text"
                name="group"
                placeholder="Usuários"
                class="w-full border p-2 rounded mb-4"
                required
            >

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closePermissionCreateModal()"
                    class="px-4 py-2"
                >
                    Cancelar
                </button>

                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Salvar
                </button>
            </div>

        </form>

    </div>
</div>