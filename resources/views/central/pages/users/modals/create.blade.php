<div
    x-data="initializeCreateUserModal()"
    x-show="showCreate"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50"
>
    <div class="w-full max-w-2xl rounded-xl bg-white p-6">

        <!-- HEADER -->
        <div class="mb-4 flex justify-between">
            <h2 class="text-lg font-semibold text-gray-800">
                Novo Usuário
            </h2>

            <button type="button" @click="showCreate = false">✕</button>
        </div>

        <form method="POST" action="{{ route('central.users.store') }}" class="space-y-4">
            @csrf

            <!-- NAME -->
            <input type="text" name="name" placeholder="Nome"
                   class="w-full rounded-lg border px-3 py-2"/>

            <!-- EMAIL -->
            <input type="email" name="email" placeholder="Email"
                   class="w-full rounded-lg border px-3 py-2"/>

            <!-- PASSWORD -->
            <input type="password" name="password" placeholder="Senha"
                   class="w-full rounded-lg border px-3 py-2"/>

            <input type="password" name="password_confirmation"
                   placeholder="Confirmar senha"
                   class="w-full rounded-lg border px-3 py-2"/>

            <!-- ROLE -->
            <select
                name="role"
                class="w-full rounded-lg border px-3 py-2"
                @change="handleRoleChange($event)"
            >
                <option value="">Selecione uma Regra</option>

                @foreach($roles as $role)
                    <option
                        value="{{ $role->name }}"
                        data-role='@json($role)'
                    >
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>

            <!-- STATUS SWITCH -->
            <div class="flex items-center justify-between rounded-lg border px-3 py-2">
                <span class="text-sm text-gray-700">Ativo</span>

                <button
                    type="button"
                    @click="isActive = !isActive"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition"
                    :class="isActive ? 'bg-brand-500' : 'bg-gray-300'"
                >
                    <span
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                        :class="isActive ? 'translate-x-6' : 'translate-x-1'"
                    ></span>
                </button>

                <input type="hidden" name="is_active" :value="isActive ? 1 : 0">
            </div>

            <!-- PERMISSIONS -->
            <div>
                <p class="mb-2 text-sm font-medium text-gray-800">
                    Permissões extras
                </p>

                <div class="grid grid-cols-2 gap-2 max-h-40 overflow-auto">

                    <template x-for="permission in permissions" :key="permission.name">
                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-lg border px-3 py-2 text-sm transition"
                            :class="isRolePermission(permission.name)
                                ? 'bg-gray-100 opacity-60 cursor-not-allowed'
                                : selectedPermissions.includes(permission.name)
                                    ? 'bg-brand-50 border-brand-200'
                                    : 'bg-white hover:bg-gray-50'"
                            @click.prevent="togglePermission(permission.name)"
                        >
                            <input
                                type="checkbox"
                                class="pointer-events-none h-4 w-4 rounded border-gray-300 text-brand-500"
                                :checked="selectedPermissions.includes(permission.name)"
                                :disabled="isRolePermission(permission.name)"
                            >

                            <!-- LABEL VISUAL (alias) -->
                            <span
                                class="select-none"
                                :class="isRolePermission(permission.name)
                                    ? 'text-gray-400'
                                    : 'text-gray-700'"
                                x-text="permission.alias"
                            ></span>

                            <span
                                x-show="isRolePermission(permission.name)"
                                class="ml-auto rounded-full bg-gray-200 px-2 py-0.5 text-xs text-gray-500"
                            >
                                Regra
                            </span>
                        </label>
                    </template>

                </div>
            </div>

            <!-- SUBMIT -->
            <button class="w-full rounded-lg bg-brand-500 py-2 text-white">
                Criar usuário
            </button>

        </form>

    </div>
</div>

@push('script')
<script>
function initializeCreateUserModal() {
    return {

        isActive: true,

        rolePermissions: [],
        selectedPermissions: [],

        // agora vem OBJETO: { name, alias }
        permissions: @json(
            $permissions->map(fn($p) => [
                'name' => $p->name,
                'alias' => $p->alias ?? $p->name
            ])
        ),

        handleRoleChange(event) {
            const option = event.target.selectedOptions[0]
            if (!option) return

            let role = {}

            try {
                role = JSON.parse(option.dataset.role || '{}')
            } catch (e) {
                role = {}
            }

            this.onRoleChange(role)
        },

        onRoleChange(role) {
            if (!role || !role.permissions) {
                this.rolePermissions = []
                this.selectedPermissions = []
                return
            }

            // SEMPRE trabalha com NAME (regra real)
            this.rolePermissions = role.permissions.map(p => p.name)

            // reset + aplica role automaticamente
            this.selectedPermissions = [...this.rolePermissions]
        },

        togglePermission(name) {
            if (this.rolePermissions.includes(name)) return

            if (this.selectedPermissions.includes(name)) {
                this.selectedPermissions = this.selectedPermissions.filter(p => p !== name)
            } else {
                this.selectedPermissions.push(name)
            }
        },

        isRolePermission(name) {
            return this.rolePermissions.includes(name)
        }
    }
}
</script>
@endpush