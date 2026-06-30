<div
    x-show="showEdit"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50"
>
    <div class="w-full max-w-2xl rounded-xl bg-white p-6  shadow-xl">

        <!-- HEADER -->
        <div class="mb-4 flex justify-between">
            <h2 class="text-lg font-semibold text-gray-800 ">
                Editar Usuário
            </h2>

            <button type="button" @click="closeEdit()">✕</button>
        </div>

        <!-- 🔥 ISSO AQUI É O FIX PRINCIPAL -->
        <template x-if="editUser">

            <form
                :action="'{{ route('central.users.update', '__ID') }}'.replace('__ID', editUser.id)"
                method="POST"
                class="space-y-4"
            >
                @csrf
                @method('PUT')

                <!-- NAME -->
                <input
                    type="text"
                    name="name"
                    x-model="editUser.name"
                    class="w-full rounded-lg border px-3 py-2  text-gray-900"
                />

                <!-- EMAIL -->
                <input
                    type="email"
                    name="email"
                    x-model="editUser.email"
                    class="w-full rounded-lg border px-3 py-2  text-gray-900"
                />

                <!-- PASSWORD -->
                <input
                    type="password"
                    name="password"
                    placeholder="Nova senha"
                    class="w-full rounded-lg border px-3 py-2  text-gray-900"
                />

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirmar senha"
                    class="w-full rounded-lg border px-3 py-2  text-gray-900"
                />

                <!-- ROLE -->
                <select
                    name="role"
                    class="w-full rounded-lg border px-3 py-2  text-gray-900"
                    @change="handleEditRoleChange($event)"
                >
                    <option value="">Selecione uma Regra</option>

                    @foreach($roles as $role)
                        <option
                            value="{{ $role->name }}"
                            data-role='@json($role)'
                            :selected="editUser.roles?.[0]?.name === '{{ $role->name }}'"
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>

                <!-- STATUS -->
                <div class="flex items-center justify-between rounded-lg border px-3 py-2">
                    <span class="text-sm text-gray-700 ">Ativo</span>

                    <button
                        type="button"
                        @click="editUser.is_active = !editUser.is_active"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition"
                        :class="editUser.is_active ? 'bg-brand-500' : 'bg-gray-300'"
                    >
                        <span
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                            :class="editUser.is_active ? 'translate-x-6' : 'translate-x-1'"
                        ></span>
                    </button>

                    <input type="hidden" name="is_active" :value="editUser.is_active ? 1 : 0">
                </div>

                <!-- PERMISSIONS -->
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-800">
                        Permissões extras
                    </p>

                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-auto">

                        <template x-for="permission in permissions" :key="permission.name">

                            <label
                                class="flex items-center gap-3 rounded-lg border px-3 py-2 text-sm transition"
                                :class="isRolePermission(permission.name)
                                    ? 'opacity-60 bg-gray-50 cursor-not-allowed'
                                    : isSelected(permission.name)
                                        ? 'bg-brand-50 border-brand-200'
                                        : 'bg-white hover:bg-gray-50 cursor-pointer'"
                                @click.prevent="togglePermission(permission.name)"
                            >

                                <input
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-brand-500 pointer-events-none"
                                    :checked="isSelected(permission.name)"
                                    :disabled="isRolePermission(permission.name)"
                                    name="permissions[]"
                                    :value="permission.name"
                                >

                                <span x-text="permission.alias"></span>

                            </label>

                        </template>

                    </div>
                </div>

                <button class="w-full rounded-lg bg-brand-500 py-2 text-white">
                    Atualizar usuário
                </button>

            </form>

        </template>

    </div>
</div>