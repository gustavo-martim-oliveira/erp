@extends('central.layout.app')

@section('content')

<div x-data="{ tab: 'database' }" class="space-y-6">

    {{-- TABS --}}
    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800">

        <button
            @click="tab='database'"
            class="px-4 py-2 text-sm font-medium transition rounded-t-lg"
            :class="tab==='database'
                ? 'border-b-2 border-brand-500 text-brand-600 dark:text-brand-400'
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
        >
            Banco de Dados
        </button>

    </div>

    <form method="POST" action="{{ route('central.settings.update') }}" class="space-y-6">
        @csrf


        {{-- =====================
            DATABASE
        ====================== --}}
        <div
            x-show="tab==='database'"
            class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 space-y-4"
        >
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Banco de Dados
            </h2>

            <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <input
                    type="checkbox"
                    name="database_backup_enabled"
                    value="1"
                    class="rounded border-gray-300 dark:border-gray-700"
                    @checked(($settings['database_backup_enabled']->value ?? false))
                >
                Ativar backup automático
            </label>

            <button
                formaction="{{ route('central.settings.export-db') }}"
                formmethod="POST"
                class="w-fit rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm"
            >
                Exportar banco agora
            </button>
        </div>

        {{-- SUBMIT --}}
        <div class="flex justify-end">
            <button class="rounded-lg bg-brand-500 hover:bg-brand-600 text-white px-6 py-2 text-sm font-medium">
                Salvar configurações
            </button>
        </div>

    </form>

</div>

@endsection