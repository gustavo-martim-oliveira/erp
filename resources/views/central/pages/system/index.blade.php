@extends('central.layout.app')

@section('content')

<div x-data="{ tab: 'system' }" class="space-y-6">

    {{-- TABS --}}
    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800">

        <button
            @click="tab='system'"
            class="px-4 py-2 text-sm font-medium transition rounded-t-lg"
            :class="tab==='system'
                ? 'border-b-2 border-brand-500 text-brand-600 dark:text-brand-400'
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
        >
            Sistema
        </button>

        <button
            @click="tab='mail'"
            class="px-4 py-2 text-sm font-medium transition rounded-t-lg"
            :class="tab==='mail'
                ? 'border-b-2 border-brand-500 text-brand-600 dark:text-brand-400'
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
        >
            Email
        </button>

        <button
            @click="tab='database'"
            class="px-4 py-2 text-sm font-medium transition rounded-t-lg"
            :class="tab==='database'
                ? 'border-b-2 border-brand-500 text-brand-600 dark:text-brand-400'
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
        >
            Banco de Dados
        </button>

        <button
            @click="tab='redis'"
            class="px-4 py-2 text-sm font-medium transition rounded-t-lg"
            :class="tab==='redis'
                ? 'border-b-2 border-brand-500 text-brand-600 dark:text-brand-400'
                : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
        >
            Redis
        </button>

    </div>

    <form method="POST" action="{{ route('central.settings.update') }}" class="space-y-6">
        @csrf

        {{-- =====================
            SISTEMA
        ====================== --}}
        <div
            x-show="tab==='system'"
            class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 space-y-4"
        >
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Configurações do Sistema
            </h2>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Nome do sistema</label>
                <input
                    name="system.name"
                    value="{{ $settings['system.name']->value ?? '' }}"
                    class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700
                           bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                           px-3 py-2 focus:ring-2 focus:ring-brand-500 outline-none"
                >
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Logo</label>
                <input
                    type="file"
                    name="system.logo"
                    class="mt-1 w-full text-sm text-gray-600 dark:text-gray-300"
                >
            </div>
        </div>

        {{-- =====================
            EMAIL
        ====================== --}}
        <div
            x-show="tab==='mail'"
            class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 space-y-4"
        >
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Configuração de Email (SMTP)
            </h2>

            <input class="input" name="mail.host" value="{{ $settings['mail.host']->value ?? '' }}" placeholder="SMTP Host">
            <input class="input" name="mail.port" value="{{ $settings['mail.port']->value ?? '' }}" placeholder="Porta">
            <input class="input" name="mail.username" value="{{ $settings['mail.username']->value ?? '' }}" placeholder="Usuário">
            <input class="input" type="password" name="mail.password" value="{{ $settings['mail.password']->value ?? '' }}" placeholder="Senha">
        </div>

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
                    name="database.backup_enabled"
                    value="1"
                    class="rounded border-gray-300 dark:border-gray-700"
                    @checked(($settings['database.backup_enabled']->value ?? false))
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

        {{-- =====================
            REDIS
        ====================== --}}
        <div
            x-show="tab==='redis'"
            class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 space-y-4"
        >
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Redis / Filas
            </h2>

            <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300">
                <input
                    type="checkbox"
                    name="redis.enabled"
                    value="1"
                    class="rounded border-gray-300 dark:border-gray-700"
                    @checked(($settings['redis.enabled']->value ?? false))
                >
                Ativar gerenciamento de filas
            </label>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Host</label>
                <input
                    name="redis.host"
                    value="{{ $settings['redis.host']->value ?? '' }}"
                    class="input"
                >
            </div>

            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Porta</label>
                <input
                    name="redis.port"
                    value="{{ $settings['redis.port']->value ?? '' }}"
                    class="input"
                >
            </div>
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