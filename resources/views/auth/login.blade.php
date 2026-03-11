<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Universitario — Iniciar Sesión</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CDN (for standalone use) -->
    <script src="https://cdn.tailwindcss.com"></script>

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
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out both',
                        'fade-in': 'fadeIn 0.8s ease-out both',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* ── Left panel: campus photo with deep indigo overlay ── */
        .hero-panel {
            background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1200&q=85&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(15, 29, 79, 0.88) 0%,
                    rgba(30, 58, 138, 0.80) 50%,
                    rgba(37, 99, 235, 0.72) 100%);
        }

        /* ── Glassmorphism card ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.20);
        }

        /* ── Input focus ring ── */
        .input-field:focus {
            outline: none;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.18);
        }

        /* ── Login button gradient ── */
        .btn-login {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1e2f6e 0%, #1d4ed8 100%);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.45);
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.30);
        }

        /* ── Custom checkbox ── */
        .custom-check {
            accent-color: #1e3a8a;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* ── Subtle inner shadow on inputs ── */
        .input-wrap {
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        /* ── Stat badge ── */
        .stat-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        /* ── Scrollbar hidden for form panel ── */
        .form-panel::-webkit-scrollbar {
            display: none;
        }

        .form-panel {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-slate-100 font-inter antialiased">

    <div class="min-h-screen flex">

        <!-- ══════════════ LEFT PANEL ══════════════ -->
        <div class="hidden lg:flex lg:w-1/2 hero-panel flex-col justify-between p-12 relative overflow-hidden">

            <!-- Decorative circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -bottom-32 -right-20 w-80 h-80 rounded-full opacity-10 bg-white"></div>

            <!-- Top: Logo + Name -->
            <div class="relative z-10 animate-fade-in" style="animation-delay: 0.1s">
                <a href="{{ url('/') }}" class="flex items-center gap-3 mb-2 group">
                    <!-- University Icon -->
                    <div
                        class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/30 group-hover:bg-white/30 transition-all">
                        <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs font-medium tracking-widest uppercase">Portal Académico</p>
                        <h1 class="text-white font-bold text-lg leading-tight">UniPortal</h1>
                    </div>
                </a>
            </div>

            <!-- Center: Main quote -->
            <div class="relative z-10 animate-fade-in-up" style="animation-delay: 0.25s">
                <div class="glass-card rounded-3xl p-8 max-w-md">
                    <div class="w-10 h-1 bg-blue-400 rounded-full mb-6"></div>
                    <h2 class="text-white text-3xl font-bold leading-tight mb-4">
                        El conocimiento es el pasaporte al futuro.
                    </h2>
                    <p class="text-white/70 text-sm leading-relaxed">
                        Accede a tu expediente académico, horarios, notas y recursos educativos desde un solo lugar. Tu
                        universidad, siempre contigo.
                    </p>
                </div>
            </div>

            <!-- Bottom: Stats -->
            <div class="relative z-10 animate-fade-in" style="animation-delay: 0.45s">
                <div class="flex gap-4">
                    <div class="stat-badge rounded-2xl px-5 py-3 text-center">
                        <p class="text-white font-bold text-xl">12k+</p>
                        <p class="text-white/60 text-xs mt-0.5">Estudiantes</p>
                    </div>
                    <div class="stat-badge rounded-2xl px-5 py-3 text-center">
                        <p class="text-white font-bold text-xl">340+</p>
                        <p class="text-white/60 text-xs mt-0.5">Docentes</p>
                    </div>
                    <div class="stat-badge rounded-2xl px-5 py-3 text-center">
                        <p class="text-white font-bold text-xl">80+</p>
                        <p class="text-white/60 text-xs mt-0.5">Programas</p>
                    </div>
                </div>
            </div>

        </div><!-- /LEFT PANEL -->


        <!-- ══════════════ RIGHT PANEL ══════════════ -->
        <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-6 sm:p-10 form-panel overflow-y-auto">

            <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.15s">

                <!-- ── Mobile logo (visible < lg) ── -->
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <div class="w-11 h-11 rounded-xl bg-brand-800 flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-medium tracking-widest uppercase">Portal Académico</p>
                        <p class="text-gray-900 font-bold text-base">UniPortal</p>
                    </div>
                </div>

                <!-- ── University Logo Space ── -->
                <div class="flex justify-center mb-6">
                    <a href="{{ url('/') }}" class="group transition-transform hover:scale-105 active:scale-95">
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-800 to-blue-500 flex items-center justify-center shadow-xl shadow-blue-900/20 group-hover:shadow-blue-900/30 transition-all">
                            <i class="fa-solid fa-graduation-cap text-white text-3xl"></i>
                        </div>
                    </a>
                </div>

                <!-- ── Heading ── -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Bienvenido de nuevo</h2>
                    <p class="text-gray-500 text-sm mt-1">Ingresa tus credenciales para acceder</p>
                </div>

                <!-- ── Session Status (Breeze) ── -->
                @if (session('status'))
                    <div
                        class="mb-5 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-5 p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- ── FORM ── -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400 text-sm"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="tu@universidad.edu"
                                class="input-field input-wrap w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm placeholder-gray-400 transition-all duration-200 @error('email') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Contraseña
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </span>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="input-field input-wrap w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm placeholder-gray-400 transition-all duration-200 @error('password') border-red-400 bg-red-50 @enderror">
                            <!-- Toggle visibility -->
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                aria-label="Mostrar contraseña">
                                <i class="fa-regular fa-eye text-sm" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="custom-check rounded border-gray-300 text-brand-800 focus:ring-brand-800">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors select-none">
                                Mantener sesión iniciada
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-medium text-brand-800 hover:text-blue-500 transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="btn-login w-full py-3.5 rounded-xl text-white font-semibold text-sm tracking-wide mt-2 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Iniciar Sesión
                    </button>

                    <!-- Registration Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500">
                            ¿Aún no tienes cuenta?
                            <a href="{{ route('register') }}"
                                class="text-brand-800 font-bold hover:text-brand-600 transition-colors underline decoration-brand-100 underline-offset-4">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>

                </form><!-- /FORM -->

                <!-- ── Divider ── -->
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium">Portal Universitario</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <!-- ── Footer note ── -->
                <p class="text-center text-xs text-gray-400 leading-relaxed">
                    ¿Problemas para acceder? Contacta a
                    <a href="mailto:soporte@universidad.edu" class="text-brand-800 hover:underline font-medium">
                        soporte@universidad.edu
                    </a>
                </p>

            </div>
        </div><!-- /RIGHT PANEL -->

    </div><!-- /main flex -->

    <!-- ── Toggle password visibility script ── -->
    <script>
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye', !isHidden);
            eyeIcon.classList.toggle('fa-eye-slash', isHidden);
        });
    </script>

</body>

</html>