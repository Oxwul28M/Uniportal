<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Universitario — Registro en Revisión</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CDN -->
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

        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.20);
        }

        .btn-action {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background: linear-gradient(135deg, #1e2f6e 0%, #1d4ed8 100%);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.45);
            transform: translateY(-2px);
        }

        .stat-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
    </style>
</head>

<body class="bg-slate-100 font-inter antialiased">

    <div class="min-h-screen flex">

        <!-- ══════════════ LEFT PANEL ══════════════ -->
        <div class="hidden lg:flex lg:w-1/2 hero-panel flex-col justify-between p-12 relative overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -bottom-32 -right-20 w-80 h-80 rounded-full opacity-10 bg-white"></div>

            <div class="relative z-10 animate-fade-in" style="animation-delay: 0.1s">
                <a href="{{ url('/') }}" class="flex items-center gap-3 mb-2 group">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/30 group-hover:bg-white/30 transition-all">
                        <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs font-medium tracking-widest uppercase">Portal Académico</p>
                        <h1 class="text-white font-bold text-lg leading-tight">UniPortal</h1>
                    </div>
                </a>
            </div>

            <div class="relative z-10 animate-fade-in-up" style="animation-delay: 0.25s">
                <div class="glass-card rounded-3xl p-8 max-w-md">
                    <div class="w-10 h-1 bg-yellow-400 rounded-full mb-6"></div>
                    <h2 class="text-white text-3xl font-bold leading-tight mb-4">
                        Tu solicitud está siendo procesada.
                    </h2>
                    <p class="text-white/70 text-sm leading-relaxed">
                        En UniPortal nos tomamos en serio la seguridad. Un administrador revisará tus datos para asegurar que todo esté en orden antes de darte acceso completo.
                    </p>
                </div>
            </div>

            <div class="relative z-10 animate-fade-in" style="animation-delay: 0.45s">
                <div class="flex gap-4">
                    <div class="stat-badge rounded-2xl px-5 py-3 text-center">
                        <p class="text-white font-bold text-xl">Seguridad</p>
                        <p class="text-white/60 text-xs mt-0.5">Prioritaria</p>
                    </div>
                    <div class="stat-badge rounded-2xl px-5 py-3 text-center">
                        <p class="text-white font-bold text-xl">Revisión</p>
                        <p class="text-white/60 text-xs mt-0.5">Manual</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════ RIGHT PANEL ══════════════ -->
        <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-6 sm:p-10 overflow-y-auto">
            <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.15s">
                
                <!-- Mobile logo -->
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <div class="w-11 h-11 rounded-xl bg-brand-800 flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-medium tracking-widest uppercase">Portal Académico</p>
                        <p class="text-gray-900 font-bold text-base">UniPortal</p>
                    </div>
                </div>

                <!-- Status Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-24 h-24 rounded-3xl bg-amber-50 flex items-center justify-center border border-amber-100 shadow-inner">
                        <i class="fa-solid fa-user-clock text-amber-500 text-4xl animate-pulse"></i>
                    </div>
                </div>

                <!-- Heading -->
                <div class="text-center mb-8">
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-amber-700 text-xs font-bold animate-fade-in flex items-center gap-3">
                            <i class="fa-solid fa-circle-info text-amber-500"></i>
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2 class="text-2xl font-bold text-gray-900 leading-tight">Cuenta en Revisión</h2>
                    <p class="text-gray-500 text-sm mt-3 px-6">
                        Hemos recibido tu solicitud de registro. Actualmente está en espera de aprobación por parte del personal administrativo.
                    </p>
                </div>

                <!-- Info Card -->
                <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100/50 mb-8">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-envelope-open-text text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 mb-1">¿Qué sigue?</p>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Una vez que tu cuenta sea verificada, recibirás un correo electrónico de confirmación para que puedas iniciar sesión.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Return Button -->
                <a href="{{ route('login') }}" class="btn-action w-full py-4 rounded-xl text-white font-bold text-sm tracking-wide flex items-center justify-center gap-3 shadow-lg shadow-indigo-200">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al Inicio de Sesión
                </a>

                <!-- Support Footer -->
                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">
                        ¿Tienes dudas? Escríbenos a
                        <a href="mailto:soporte@universidad.edu" class="text-brand-800 font-bold hover:underline">soporte@universidad.edu</a>
                    </p>
                </div>

            </div>
        </div>

    </div>
</body>
</html>
