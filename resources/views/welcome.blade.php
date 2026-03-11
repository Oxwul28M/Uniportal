<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Universitario — Inicio</title>
    <meta name="description"
        content="Portal académico universitario. Accede a tus notas, horarios, noticias y eventos institucionales.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { inter: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe',
                            300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6',
                            600: '#2563eb', 700: '#1d4ed8', 800: '#1e3a8a',
                            900: '#1e2f6e', 950: '#0f1d4f',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Hero */
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1600&q=85&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 29, 79, 0.92) 0%, rgba(30, 58, 138, 0.82) 60%, rgba(37, 99, 235, 0.65) 100%);
        }

        /* Card hover - Noticias (horizontal) */
        .news-card {
            transition: transform 0.28s cubic-bezier(.22,.68,0,1.2), box-shadow 0.28s ease;
        }
        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(30,58,138,0.13);
        }

        /* Card hover - Eventos (vertical) */
        .event-card {
            transition: transform 0.28s cubic-bezier(.22,.68,0,1.2), box-shadow 0.28s ease;
        }
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(30,58,138,0.14);
        }

        /* Badge genérico */
        .badge-academico  { background:#dbeafe; color:#1e3a8a; }
        .badge-deportes   { background:#dcfce7; color:#14532d; }
        .badge-pagos      { background:#fef3c7; color:#92400e; }
        .badge-noticia    { background:#dbeafe; color:#1e3a8a; }
        .badge-evento     { background:#ede9fe; color:#4c1d95; }
        .badge-cultural   { background:#fce7f3; color:#831843; }

        /* Botón leer más */
        .news-read-more {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.2s ease;
        }

        /* Nav glass */
        .nav-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeup {
            animation: fadeUp 0.6s ease-out both;
        }
    </style>
</head>

<body class="bg-slate-50 text-gray-900 antialiased">

    {{-- ═══════════════════════════════════════════════
    NAVBAR
    ═══════════════════════════════════════════════ --}}
    <nav class="absolute top-0 left-0 right-0 z-20 nav-glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center">
                    <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-base leading-tight">UniPortal</p>
                    <p class="text-white/50 text-xs">Portal Académico</p>
                </div>
            </div>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-2">
                <a href="#noticias"
                    class="text-white/80 hover:text-white text-sm px-4 py-2 rounded-lg hover:bg-white/10 transition-all">Noticias</a>
                <a href="#eventos"
                    class="text-white/80 hover:text-white text-sm px-4 py-2 rounded-lg hover:bg-white/10 transition-all">Eventos</a>

                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="ml-4 bg-white text-brand-800 font-semibold text-sm px-5 py-2 rounded-xl hover:bg-blue-50 transition-all shadow-lg">
                        <i class="fa-solid fa-gauge-high mr-1.5"></i>Mi Panel
                    </a>
                @else
                    <div class="flex items-center gap-3 ml-4">
                        <a href="{{ route('login') }}"
                            class="text-white border border-white/30 text-sm px-5 py-2 rounded-xl hover:bg-white/10 transition-all">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-white text-brand-800 font-bold text-sm px-5 py-2 rounded-xl hover:bg-brand-50 transition-all shadow-lg">
                            Registro
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════
    HERO SECTION
    ═══════════════════════════════════════════════ --}}
    <section class="hero-bg relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto animate-fadeup">

            {{-- Badge --}}
            <div
                class="inline-flex items-center gap-2 bg-white/15 backdrop-blur border border-white/25 rounded-full px-5 py-2 mb-8">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping inline-block"></span>
                <span class="text-white/90 text-sm font-medium">Sistema activo — Período 2025-I</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6">
                Tu Universidad,<br>
                <span class="text-transparent bg-clip-text"
                    style="background-image: linear-gradient(135deg, #60a5fa, #a78bfa);">
                    Siempre Contigo
                </span>
            </h1>

            <p class="text-white/70 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                Accede a tus notas, horarios, pagos y toda la información institucional desde un solo lugar.
                Rápido, seguro y siempre disponible.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="flex items-center gap-2 bg-white text-brand-800 font-bold px-8 py-4 rounded-2xl shadow-2xl hover:bg-blue-50 transition-all text-base">
                        <i class="fa-solid fa-gauge-high"></i> Ir a Mi Panel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 text-white font-bold px-8 py-4 rounded-2xl border-2 border-white/40 hover:bg-white/10 transition-all text-base">
                        <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
                    </a>
                @endauth
                <a href="#noticias"
                    class="flex items-center gap-2 bg-white/10 backdrop-blur text-white font-semibold px-8 py-4 rounded-2xl border border-white/20 hover:bg-white/20 transition-all text-base">
                    <i class="fa-regular fa-newspaper"></i> Ver Noticias
                </a>
            </div>

            {{-- Stats row --}}
            <div class="flex flex-wrap justify-center gap-6 mt-16">
                @foreach([['12k+', 'Estudiantes', 'fa-users'], ['340+', 'Docentes', 'fa-chalkboard-user'], ['80+', 'Programas', 'fa-book-open']] as [$num, $lab, $ico])
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl px-6 py-4 text-center">
                        <i class="fa-solid {{ $ico }} text-blue-300 text-xl mb-2 block"></i>
                        <p class="text-white font-black text-2xl">{{ $num }}</p>
                        <p class="text-white/60 text-xs mt-0.5">{{ $lab }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
            <i class="fa-solid fa-chevron-down text-white/40 text-xl"></i>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
    SECCIÓN NOTICIAS — Diseño Horizontal Moderno
    ═══════════════════════════════════════════════ --}}
    <section id="noticias" class="py-20 px-4 sm:px-6 bg-slate-50">
        <div class="max-w-7xl mx-auto">

            {{-- Header de sección --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-brand-100 text-brand-800 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wide">
                        <i class="fa-regular fa-newspaper"></i> Últimas Noticias
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">Mantente Informado</h2>
                    <p class="text-slate-500 text-sm mt-1.5">Comunicados y novedades institucionales</p>
                </div>
                <a href="#eventos" class="self-start sm:self-auto inline-flex items-center gap-2 text-brand-700 text-sm font-semibold hover:text-brand-900 transition-colors">
                    Ver agenda de eventos <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            {{-- Grid de Noticias — tarjetas HORIZONTALES --}}
            @if($news->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    @foreach($news as $post)
                        @php
                            $catLabel = match($post->category ?? 'noticia') {
                                'noticia' => ['cls' => 'badge-academico', 'ico' => 'fa-newspaper',      'txt' => 'Académico'],
                                'evento'  => ['cls' => 'badge-evento',    'ico' => 'fa-calendar-check', 'txt' => 'Evento'],
                                default   => ['cls' => 'badge-noticia',   'ico' => 'fa-circle-info',    'txt' => 'Noticia'],
                            };
                        @endphp
                        <article class="news-card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-row" id="noticia-{{ $post->id }}">

                            {{-- Imagen (30%) --}}
                            <div class="news-img-wrap relative" style="width:35%;min-height:180px;flex-shrink:0;overflow:hidden;">
                                @if($post->image_url)
                                    <img src="{{ $post->image_url }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                         style="min-height:180px;">
                                @else
                                    <div class="news-img-ph w-full h-full flex items-center justify-center"
                                         style="min-height:180px;background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 100%);">
                                        <i class="fa-regular fa-newspaper text-white/25 text-3xl"></i>
                                    </div>
                                @endif
                                {{-- Badge de categoría flotante --}}
                                <span class="absolute top-3 left-3 {{ $catLabel['cls'] }} text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                                    {{ $catLabel['txt'] }}
                                </span>
                            </div>

                            {{-- Contenido (65%) --}}
                            <div class="flex flex-col justify-between p-5" style="width:65%;">
                                <div>
                                    {{-- Meta: autor + tiempo --}}
                                    {{-- Meta: tiempo --}}
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fa-regular fa-clock text-slate-400 text-[10px]"></i>
                                        <span class="text-slate-400 text-xs font-medium">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>

                                    {{-- Título --}}
                                    <h3 class="font-bold text-slate-800 text-base leading-snug mb-2 line-clamp-2">
                                        {{ $post->title }}
                                    </h3>

                                    {{-- Extracto --}}
                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-2">
                                        {{ $post->content }}
                                    </p>
                                </div>

                                {{-- Pie: fecha + botón leer más --}}
                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                                    <span class="text-slate-400 text-xs">
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        {{ $post->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

            @else
                {{-- Estado vacío --}}
                <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-brand-200">
                    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-regular fa-newspaper text-brand-300 text-3xl"></i>
                    </div>
                    <p class="text-slate-600 font-semibold">No hay noticias publicadas aún.</p>
                    <p class="text-slate-400 text-sm mt-1">Las novedades institucionales aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
    SECCIÓN EVENTOS — Diseño Vertical con Fecha-Calendario
    ═══════════════════════════════════════════════ --}}
    <section id="eventos" class="py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-7xl mx-auto">

            {{-- Header de sección --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-800 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wide">
                        <i class="fa-regular fa-calendar-days"></i> Próximos Eventos
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">Agenda Universitaria</h2>
                    <p class="text-slate-500 text-sm mt-1.5">Eventos, charlas y actividades académicas</p>
                </div>
                <a href="#noticias" class="self-start sm:self-auto inline-flex items-center gap-2 text-indigo-700 text-sm font-semibold hover:text-indigo-900 transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Ver noticias
                </a>
            </div>

            @if($events->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($events as $post)
                        @php
                            // Determinar badge visual del evento
                            $evBadge = ['cls' => 'badge-evento', 'txt' => 'Evento'];
                            // Color del cuadro de fecha según índice
                            $calColors = [
                                'from-brand-800 to-blue-600',
                                'from-indigo-700 to-indigo-500',
                                'from-blue-700 to-blue-500',
                                'from-slate-700 to-slate-500',
                                'from-brand-900 to-brand-700',
                            ];
                            $calColor = $calColors[$loop->index % count($calColors)];
                        @endphp
                        <article class="event-card bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col" id="evento-{{ $post->id }}">
                            
                            {{-- Imagen del Evento (si existe) --}}
                            @if($post->image_url)
                                <div class="w-full h-48 overflow-hidden relative">
                                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                </div>
                            @endif

                            {{-- Cabecera del evento: cuadro de fecha tipo calendario --}}
                            <div class="flex items-stretch gap-0">

                                {{-- Cuadro-Calendario (azul) --}}
                                <div class="bg-gradient-to-br {{ $calColor }} flex flex-col items-center justify-center px-5 py-4 text-white flex-shrink-0" style="min-width:80px;">
                                    @if($post->event_date)
                                        <span class="cal-day text-4xl font-black leading-none">
                                            {{ $post->event_date->format('d') }}
                                        </span>
                                        <span class="text-white/75 text-xs font-bold uppercase tracking-widest mt-1">
                                            {{ $post->event_date->translatedFormat('M') }}
                                        </span>
                                        <span class="text-white/50 text-xs mt-0.5">
                                            {{ $post->event_date->format('Y') }}
                                        </span>
                                    @else
                                        <i class="fa-regular fa-calendar-check text-white/50 text-2xl"></i>
                                        <span class="text-white/60 text-xs mt-1">Pronto</span>
                                    @endif
                                </div>

                                {{-- Meta info a la derecha del calendario --}}
                                <div class="flex flex-col justify-center px-4 py-3 bg-slate-50 flex-1 border-b border-slate-100">
                                    {{-- Badge de categoría --}}
                                    <span class="{{ $evBadge['cls'] }} text-xs font-bold px-2.5 py-1 rounded-full self-start mb-1">
                                        {{ $evBadge['txt'] }}
                                    </span>
                                    @if($post->event_location)
                                        <div class="flex items-center gap-1 text-slate-500 text-xs mt-1">
                                            <i class="fa-solid fa-location-dot text-brand-600 text-xs"></i>
                                            <span class="line-clamp-1">{{ $post->event_location }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Cuerpo de la tarjeta --}}
                            <div class="flex flex-col flex-1 p-5">
                                {{-- Título --}}
                                <h3 class="font-bold text-slate-800 text-base leading-snug mb-2 line-clamp-2">
                                    {{ $post->title }}
                                </h3>

                                {{-- Descripción --}}
                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 flex-1">
                                    {{ $post->content }}
                                </p>

                                {{-- Pie: autor + botón --}}
                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                                    <div></div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

            @else
                <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-brand-200">
                    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-regular fa-calendar text-brand-300 text-3xl"></i>
                    </div>
                    <p class="text-slate-600 font-semibold">No hay eventos programados actualmente.</p>
                    <p class="text-slate-400 text-sm mt-1">Los próximos eventos institucionales aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
    FOOTER
    ═══════════════════════════════════════════════ --}}
    <footer class="bg-gray-900 text-white py-12 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-graduation-cap text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-base">UniPortal</p>
                    <p class="text-white/40 text-xs">© {{ date('Y') }} Todos los derechos reservados</p>
                </div>
            </div>
            <div class="flex gap-6 text-sm text-white/50">
                <a href="#noticias" class="hover:text-white transition-colors">Noticias</a>
                <a href="#eventos" class="hover:text-white transition-colors">Eventos</a>
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Acceso</a>
            </div>
        </div>
    </footer>

</body>

</html>