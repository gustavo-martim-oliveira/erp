@extends('central.layout.app')

@section('content')

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 py-6 dark:border-gray-800 dark:bg-gray-900 xl:px-8 xl:py-8"
    x-data="userPage()"
>

    {{-- HEADER --}}
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                Usuários
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gerencie usuários, papéis e permissões.
            </p>
        </div>

        <button
            @click="openCreate()"
            class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
        >
            + Novo usuário
        </button>

    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <form method="GET" class="mb-6 flex gap-3">
        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Buscar por nome ou email..."
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
        />

        <button class="rounded-lg bg-gray-900 px-4 py-2 text-sm text-white dark:bg-white dark:text-gray-900">
            Buscar
        </button>
    </form>

    {{-- TABLE --}}
    <div class="overflow-x-auto w-full rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white">

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">

            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">

                @foreach($users as $user)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">

                        <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">
                            {{ $user->name }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $user->email }}
                        </td>

                        <td class="px-4 py-3 text-sm">
                            {{ $user->roles->first()?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm">
                            @if($user->is_active)
                                <span class="text-green-500">Ativo</span>
                            @else
                                <span class="text-red-500">Inativo</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right text-sm">

                            <button
                                @click="openEdit({{ $user->id }})"
                                class="text-blue-500 hover:underline"
                            >
                                Editar
                            </button>

                            <form
                                method="POST"
                                action="{{ route('central.users.destroy', $user) }}"
                                class="inline"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Deseja excluir este usuário?')"
                                    class="ml-3 text-[#FF0000] hover:underline"
                                >
                                    Excluir
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- MODAL CREATE --}}
    @include('central.pages.users.modals.create')

    {{-- MODAL EDIT --}}
    @include('central.pages.users.modals.edit')

</div>

@endsection

@push('script')
<script>
function userPage() {
    return {

        /* =========================
         * MODAIS
         * ========================= */
        showCreate: false,
        showEdit: false,

        /* =========================
         * USER ATUAL (EDIT)
         * ========================= */
        editUser: null,

        /* =========================
         * PERMISSÕES (GLOBAL)
         * ========================= */
        permissions: @json(
            $permissions->map(fn($p) => [
                'name' => $p->name,
                'alias' => $p->alias ?? $p->name
            ])
        ),

        /* =========================
         * ESTADO DE PERMISSÕES
         * ========================= */
        rolePermissions: [],
        selectedPermissions: [],

        /* =========================
         * CREATE MODAL
         * ========================= */
        openCreate() {
            this.showCreate = true
        },

        closeCreate() {
            this.showCreate = false
        },

        /* =========================
         * EDIT MODAL (CORRIGIDO)
         * ========================= */
        openEdit(userId) {
            fetch(`/central/users/${userId}/edit`)
                .then(res => res.json())
                .then(user => {

                    this.editUser = user

                    // garante arrays válidos
                    const rolePerms = user.role_permissions ?? []
                    const directPerms = user.direct_permissions ?? []

                    // permissões da regra
                    this.rolePermissions = [...rolePerms]

                    // permissões selecionadas (regra + extras)
                    this.selectedPermissions = [
                        ...new Set([
                            ...rolePerms,
                            ...directPerms
                        ])
                    ]

                    this.showEdit = true
                })
                .catch(err => {
                    console.error('Erro ao carregar usuário:', err)
                })
        },

        closeEdit() {
            this.showEdit = false
            this.editUser = null
            this.rolePermissions = []
            this.selectedPermissions = []
        },

        /* =========================
         * ROLE CHANGE (CREATE + EDIT)
         * ========================= */
        handleRoleChange(event) {
            const option = event.target.selectedOptions[0]
            if (!option) return

            let role = {}

            try {
                role = JSON.parse(option.dataset.role || '{}')
            } catch (e) {}

            this.applyRole(role)
        },

        handleEditRoleChange(event) {
            this.handleRoleChange(event)
        },

        applyRole(role) {

            if (!role || !role.permissions) {
                this.rolePermissions = []
                this.selectedPermissions = []
                return
            }

            const perms = role.permissions.map(p => p.name)

            this.rolePermissions = [...perms]

            // mantém extras + regra
            this.selectedPermissions = [
                ...new Set([
                    ...perms,
                    ...this.selectedPermissions.filter(p => !this.rolePermissions.includes(p))
                ])
            ]
        },

        /* =========================
         * PERMISSION LOGIC
         * ========================= */
        togglePermission(name) {

            if (this.rolePermissions.includes(name)) return

            if (this.selectedPermissions.includes(name)) {
                this.selectedPermissions =
                    this.selectedPermissions.filter(p => p !== name)
            } else {
                this.selectedPermissions.push(name)
            }
        },

        isSelected(name) {
            return this.selectedPermissions.includes(name)
        },

        isRolePermission(name) {
            return this.rolePermissions.includes(name)
        }
    }
}
</script>
@endpush