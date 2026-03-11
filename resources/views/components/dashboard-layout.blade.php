<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniPortal') }} — Admin</title>

    <!-- Tailwind & Fonts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e3a8a',
                            900: '#1e2f6e',
                            950: '#0f1d4f',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-inter antialiased text-slate-900 min-h-screen">
    <div class="relative flex flex-col min-h-screen w-full overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
        <!-- Navigation Bar -->
        <header class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white px-6 md:px-10 lg:px-20 py-3 shadow-sm">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-10">
                    <div class="flex items-center gap-3">
                        <div class="size-9 bg-brand-800 flex items-center justify-center rounded-lg text-white shadow-sm">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <h2 class="text-gray-900 text-lg font-bold leading-tight">UniPortal @if(Auth::user()->role === 'admin') Admin @endif</h2>
                    </div>
                    <nav class="hidden md:flex items-center gap-8">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Dashboard</a>
                            <a href="{{ route('admin.requests.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('admin.requests.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Solicitudes</a>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Usuarios</a>
                            <a href="{{ route('admin.posts.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('admin.posts.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Avisos</a>
                        @elseif(Auth::user()->role === 'manager')
                            <a href="{{ route('manager.dashboard') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('manager.dashboard') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Dashboard</a>
                            <a href="{{ route('manager.users.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('manager.users.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Usuarios</a>
                            <a href="{{ route('manager.payments.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('manager.payments.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Pagos</a>
                            <a href="{{ route('manager.reports.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('manager.reports.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Reportes</a>
                            <a href="{{ route('manager.posts.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('manager.posts.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Mis Avisos</a>
                        @elseif(Auth::user()->role === 'teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('teacher.dashboard') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Dashboard</a>
                            <a href="{{ route('teacher.courses.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('teacher.courses.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Mis Cursos</a>
                            <a href="{{ route('teacher.grading.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('teacher.grading.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Calificaciones</a>
                            <a href="{{ route('teacher.agenda.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('teacher.agenda.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Agenda</a>
                        @elseif(Auth::user()->role === 'student')
                            <a href="{{ route('dashboard') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('dashboard') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Dashboard</a>
                            <a href="{{ route('student.grades') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('student.grades') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Notas</a>
                            <a href="{{ route('student.schedule') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('student.schedule') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Horario</a>
                            <a href="{{ route('student.documents') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('student.documents') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Documentos</a>
                            <a href="{{ route('student.enrollment') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('student.enrollment') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Inscripción</a>
                            <a href="{{ route('student.payments.index') }}" class="text-sm font-semibold py-1 transition-colors {{ request()->routeIs('student.payments.*') ? 'text-brand-800 border-b-2 border-brand-800' : 'text-gray-500 hover:text-brand-800 font-medium' }}">Pagos</a>
                        @endif
                    </nav>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200 focus-within:border-brand-800/50 focus-within:ring-2 focus-within:ring-brand-800/10 transition-all">
                        <span class="material-symbols-outlined text-gray-400 text-sm">search</span>
                        <input class="bg-transparent border-none focus:ring-0 text-sm placeholder:text-gray-400 w-48" placeholder="Buscar..." type="text"/>
                    </div>
                    
                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="p-2 text-gray-500 hover:bg-gray-100 hover:text-brand-800 rounded-xl transition-colors relative">
                            <span class="material-symbols-outlined">notifications</span>
                            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white"></span>
                            @endif
                        </button>
                        
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-80 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-widest">Notificaciones</p>
                                @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                                    <span class="text-[10px] font-bold bg-brand-100 text-brand-800 px-2 py-0.5 rounded-full">{{ Auth::user()->unreadNotifications->count() }} Nuevas</span>
                                @endif
                            </div>
                            <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto w-full">
                                @if(Auth::check() && Auth::user()->notifications->count() > 0)
                                    @foreach(Auth::user()->notifications->take(5) as $notification)
                                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ is_null($notification->read_at) ? 'bg-blue-50/30' : '' }}">
                                            <p class="text-xs font-semibold text-gray-900">{{ $notification->data['message'] ?? 'Nueva notificación' }}</p>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-4xl mb-2 opacity-20">notifications_off</span>
                                        <p class="text-xs font-semibold">No tienes notificaciones nuevas</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User Avatar/Menu Toggle -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none group">
                            <div class="h-9 w-9 rounded-xl flex items-center justify-center overflow-hidden transition-all group-hover:shadow-md ring-2 ring-transparent group-hover:ring-brand-100">
                                <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e3a8a&color=fff" alt="User avatar"/>
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                            <div class="p-4 bg-gray-50 border-b border-gray-100">
                                <p class="text-sm font-bold truncate text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-brand-800 uppercase tracking-widest font-semibold mt-0.5">
                                    @switch(Auth::user()->role)
                                        @case('admin')
                                            Administrador
                                            @break
                                        @case('manager')
                                            Gestor
                                            @break
                                        @case('teacher')
                                            Docente
                                            @break
                                        @case('student')
                                            Estudiante
                                            @break
                                        @default
                                            {{ ucfirst(Auth::user()->role) }}
                                    @endswitch
                                </p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-gray-600 hover:bg-gray-50 hover:text-brand-800 rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-sm">person</span> Perfil
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                        <span class="material-symbols-outlined text-sm">logout</span> Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>


        <!-- Main Content -->
        <main class="flex-1 w-full max-w-7xl mx-auto px-6 md:px-10 lg:px-20 py-8 relative" x-data="{
            toastVisible: false,
            toastMessage: '',
            toastType: 'success',
            showToast(message, type = 'success') {
                this.toastMessage = message;
                this.toastType = type;
                this.toastVisible = true;
                setTimeout(() => { this.toastVisible = false; }, 4000);
            }
        }" @notify.window="showToast($event.detail.message, $event.detail.type)">
            
            <!-- Session Messages -->
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-lg">error</span>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            {{ $slot }}

            <!-- Toast Notification -->
            <div x-show="toastVisible" x-cloak
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 rounded-2xl shadow-xl border w-full max-w-sm"
                 :class="{
                     'bg-emerald-50 border-emerald-200 text-emerald-800': toastType === 'success',
                     'bg-blue-50 border-blue-200 text-blue-800': toastType === 'info',
                     'bg-amber-50 border-amber-200 text-amber-800': toastType === 'warning',
                     'bg-red-50 border-red-200 text-red-800': toastType === 'error'
                 }">
                <span class="material-symbols-outlined text-2xl" x-text="toastType === 'success' ? 'check_circle' : (toastType === 'error' ? 'error' : 'info')"></span>
                <p class="text-sm font-bold flex-1" x-text="toastMessage"></p>
                <button @click="toastVisible = false" class="p-1 rounded-lg opacity-50 hover:opacity-100 hover:bg-black/5 transition-colors">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-auto py-8 text-center text-slate-500 dark:text-slate-500 text-sm">
            <p>© {{ date('Y') }} RegPortal Global Education Systems. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>

</html>