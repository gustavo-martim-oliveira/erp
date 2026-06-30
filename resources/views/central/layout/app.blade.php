<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
      {{ $title ?? 'Administração' }} | {{ config('app.name') }}
    </title>
    @vite([
      'resources/css/app.css',
      'resources/css/style.css',
      'resources/js/app.js',
    ])
    @stack('top-script')
  </head>
  <body
    x-data="{ page: '{{ ($page ?? $title) ?? 'Dashboard'}}', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false;

        document.documentElement.classList.toggle('dark', darkMode);

        $watch('darkMode', value => {
            localStorage.setItem('darkMode', JSON.stringify(value));
            document.documentElement.classList.toggle('dark', value);
        });
    "
    
    :class="{'dark bg-gray-900': darkMode === true}"
  >
    <!-- ===== Preloader Start ===== -->
    @include('components.preloader')
    <!-- ===== Preloader End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
      <!-- ===== Sidebar Start ===== -->
      @include('components.sidebar')
      <!-- ===== Sidebar End ===== -->

      <!-- ===== Content Area Start ===== -->
      <div
        class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden"
      >
        <!-- Small Device Overlay Start -->
        @include('components.overlay')
        <!-- Small Device Overlay End -->

        <!-- ===== Header Start ===== -->
        @include('components.header')
        <!-- ===== Header End ===== -->

        <!-- ===== Main Content Start ===== -->
        <main>
          <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
            <!-- Breadcrumb Start -->
            <div x-data="{ pageName: `{{ $title ?? 'dashboard' }}`}">
              @include('components.breadcrumb')
            </div>
            <!-- Breadcrumb End -->

            @yield('content')


          </div>
        </main>
        <!-- ===== Main Content End ===== -->
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->
    @stack('script')
  </body>
</html>
