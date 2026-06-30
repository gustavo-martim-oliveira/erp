@extends('central.layout.app')

@section('content')

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 py-6 dark:border-gray-800 dark:bg-gray-900 xl:px-8 xl:py-8"
    x-data="{
        tab: '{{ $tab ?? 'roles' }}',

        change(tabName) {
            this.tab = tabName;

            const url = new URL(window.location);

            url.searchParams.set('tab', tabName);

            window.history.replaceState({}, '', url);
        }
    }"
>

    {{-- HEADER --}}
    <div class="mb-8 flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                Controle de Acesso
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gerencie papéis e permissões do sistema.
            </p>

        </div>

    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700 dark:bg-green-900/20 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-700 dark:bg-red-900/20 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABS --}}
    <div class="mb-6 flex border-b border-gray-200 dark:border-gray-700">

        <button
            @click="change('roles')"
            class="border-b-2 px-5 py-3 text-sm font-medium transition"
            :class="tab === 'roles'
                ? 'border-brand-500 text-brand-500'
                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white'"
        >
            Papéis
        </button>

        <button
            @click="change('permissions')"
            class="border-b-2 px-5 py-3 text-sm font-medium transition"
            :class="tab === 'permissions'
                ? 'border-brand-500 text-brand-500'
                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white'"
        >
            Permissões
        </button>

    </div>

    {{-- CONTEÚDO --}}
    <div>

        <div
            x-show="tab === 'roles'"
            x-transition.opacity
            x-cloak
        >
            @include('central.pages.acl.tabs.roles')
        </div>

        <div
            x-show="tab === 'permissions'"
            x-transition.opacity
            x-cloak
        >
            @include('central.pages.acl.tabs.permissions')
        </div>

    </div>

</div>

{{-- MODAIS --}}
@include('central.pages.acl.modals.role-create')
@include('central.pages.acl.modals.role-edit')
@include('central.pages.acl.modals.permission-create')
@include('central.pages.acl.modals.permission-edit')

@endsection

@push('script')
    @vite('resources/js/pages/acl.js')
@endpush