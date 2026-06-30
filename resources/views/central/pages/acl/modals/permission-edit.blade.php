<div
    id="permissionEditModal"
    class="hidden fixed flex z-[9999999] inset-0 bg-black/50 items-center justify-center z-50"
>

    <div class="bg-white w-[520px] rounded-lg shadow-lg p-6">

        <h2 class="text-lg font-bold mb-4">
            Editar Permissão
        </h2>

        <form
            id="permissionEditForm"
            method="POST"
         >
            @csrf
            @method('PUT')

            {{-- NOME TÉCNICO --}}
            <label class="block text-sm mb-1 text-gray-700">
                Nome técnico
            </label>

            <input
                type="text"
                name="name"
                id="edit_permission_name"
                class="w-full border rounded p-2 mb-4"
                placeholder="users.create"
                required
            >

            {{-- ALIAS --}}
            <label class="block text-sm mb-1 text-gray-700">
                Alias (título)
            </label>

            <input
                type="text"
                name="alias"
                id="edit_permission_alias"
                class="w-full border rounded p-2 mb-4"
                placeholder="Criar Usuário"
                required
            >

            {{-- GRUPO --}}
            <label class="block text-sm mb-1 text-gray-700">
                Grupo
            </label>

            <input
                type="text"
                name="group"
                id="edit_permission_group"
                class="w-full border rounded p-2 mb-4"
                placeholder="Usuários"
                required
            >

            {{-- FOOTER --}}
            <div class="flex justify-end gap-2 mt-4">

                <button
                    type="button"
                    onclick="closePermissionEditModal()"
                    class="px-4 py-2 text-gray-600 hover:text-black"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Atualizar
                </button>

            </div>

        </form>

    </div>
</div>