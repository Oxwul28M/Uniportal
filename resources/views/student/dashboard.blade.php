<x-dashboard-layout>
    <!-- Welcome Banner -->
    <div class="bg-white rounded-2xl p-10 mb-8 relative overflow-hidden shadow-sm border border-gray-200">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex-1">
                <h1 class="text-3xl font-bold tracking-tight leading-tight mb-2 text-gray-900">
                    ¡Hola, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-gray-500 text-sm max-w-2xl leading-relaxed mb-6">
                    Bienvenido a tu semestre de <span class="text-brand-700 font-semibold">Ingeniería en
                    Informática</span>. Tienes <span class="text-brand-700 font-semibold">2 evaluaciones</span> pendientes
                    para esta semana.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('student.schedule') }}"
                        class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        Ver Horario de Hoy
                    </a>
                    <a href="{{ route('student.documents') }}"
                        class="bg-slate-50 border border-gray-200 text-brand-700 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">description</span>
                        Mis Trámites
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Grades Section -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-lg font-bold flex items-center gap-3 text-gray-900">
                    <span class="material-symbols-outlined text-gray-500">school</span>
                    Mis Notas Actuales
                </h3>
                <a href="{{ route('student.grades') }}"
                    class="text-brand-700 text-xs font-semibold hover:underline">Ver Historial</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grade Card 1 -->
                <div class="bg-white p-6 border-l-4 border-l-emerald-500 border border-gray-200 rounded-2xl shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-bold text-gray-900 text-base leading-none mb-1.5">Bases de Datos II</h4>
                            <p class="text-xs text-gray-500 font-medium">Prof. Juan Pérez</p>
                        </div>
                        <span class="px-2 py-1 bg-brand-50 text-brand-700 text-[10px] font-bold uppercase rounded-lg border border-brand-200">Sec. A</span>
                    </div>
                    <div class="flex items-end justify-between mt-6">
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Estado</p>
                            <span class="text-emerald-600 font-bold text-xs">APROBADO</span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Nota Final</p>
                            <p class="text-3xl font-bold text-gray-900 leading-none tracking-tighter">18.5</p>
                        </div>
                    </div>
                </div>

                <!-- Grade Card 2 -->
                <div class="bg-white p-6 border-l-4 border-l-amber-500 border border-gray-200 rounded-2xl shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-bold text-gray-900 text-base leading-none mb-1.5">Programación III</h4>
                            <p class="text-xs text-gray-500 font-medium">Ing. Marta Sánchez</p>
                        </div>
                        <span class="px-2 py-1 bg-brand-50 text-brand-700 text-[10px] font-bold uppercase rounded-lg border border-brand-200">Sec. B</span>
                    </div>
                    <div class="flex items-end justify-between mt-6">
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Estado</p>
                            <span class="text-amber-600 font-bold text-xs uppercase">En Curso</span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Nota Final</p>
                            <p class="text-3xl font-bold text-gray-400 leading-none tracking-tighter">--</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-brand-50 border border-brand-100 rounded-xl flex items-start gap-3">
                <span class="material-symbols-outlined text-brand-700 text-xl">info</span>
                <p class="text-brand-800 text-xs font-medium leading-relaxed">
                    Las notas definitivas se verán reflejadas una vez cierre el proceso académico del presente lapso.
                </p>
            </div>
        </div>

        <!-- Account Section -->
        <div class="lg:col-span-4 space-y-6">
            <h3 class="text-lg font-bold flex items-center gap-3 px-2 text-gray-900">
                <span class="material-symbols-outlined text-gray-500">account_balance_wallet</span>
                Estado de Cuenta
            </h3>

            <div class="bg-white p-6 rounded-2xl relative overflow-hidden border border-gray-200 shadow-sm">
                <div class="relative z-10">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Saldo Pendiente</p>
                    <div class="flex items-center gap-1.5 mb-8">
                        <span class="text-2xl font-bold {{ $pendingBalance > 0 ? 'text-rose-600' : 'text-brand-700' }}">$</span>
                        <h4 class="text-4xl font-bold tracking-tighter text-gray-900">{{ number_format($pendingBalance, 2) }}</h4>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('student.payments.create') }}"
                            class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white w-full py-3 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-2 shadow-sm">
                            Reportar Pago
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                        <a href="{{ route('student.payments.index') }}"
                            class="w-full bg-slate-50 text-brand-700 py-3 rounded-xl text-xs font-semibold border border-gray-200 hover:bg-slate-100 transition-all text-center block">
                            Ver Historial de Facturas
                        </a>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                        @if($pendingBalance > 0)
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(225,29,72,0.5)]"></span>
                                <span class="text-[10px] font-bold text-rose-600 uppercase tracking-widest">Pago Requerido</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Vencido</span>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Solvente</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Al día</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-lg font-bold flex items-center gap-3 text-gray-900">
                <span class="material-symbols-outlined text-gray-500">schedule</span>
                Horario de Hoy
            </h3>
            <p class="text-gray-500 text-xs font-semibold">{{ now()->format('l, d M') }}</p>
        </div>

        <div class="bg-white overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <div class="flex-1 p-6 hover:bg-gray-50 transition-all group">
                    <p class="text-brand-700 text-[10px] font-bold mb-1.5 uppercase tracking-widest">08:00 AM - 10:00 AM</p>
                    <h5 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-brand-700 transition-colors">
                        Redes de Computadoras</h5>
                    <p class="text-xs font-medium text-gray-500">Laboratorio L-204</p>
                </div>
                <div class="flex-1 p-6 hover:bg-brand-50 transition-all border-l-4 border-l-brand-600 md:border-l-0 group bg-brand-50/50">
                    <p class="text-brand-700 text-[10px] font-bold mb-1.5 uppercase tracking-widest">10:30 AM - 12:30 PM</p>
                    <h5 class="text-sm font-bold text-gray-900 mb-1 tracking-tight">Bases de Datos II</h5>
                    <p class="text-xs font-medium text-gray-500 mb-4">Aula Virtual 03</p>
                    <span class="px-2 py-1 bg-brand-600 text-[10px] font-bold text-white uppercase rounded-md shadow-sm">En Clase</span>
                </div>
                <div class="flex-1 p-6 hover:bg-gray-50 transition-all group opacity-70">
                    <p class="text-gray-500 text-[10px] font-bold mb-1.5 uppercase tracking-widest">02:00 PM - 04:00 PM</p>
                    <h5 class="text-sm font-bold text-gray-800 mb-1">Sistemas Operativos</h5>
                    <p class="text-xs font-medium text-gray-500">Auditorio Principal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <div class="mt-8 space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-lg font-bold flex items-center gap-3 text-gray-900">
                <span class="material-symbols-outlined text-brand-700">campaign</span>
                Noticias y Comunicados
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts ?? [] as $post)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                    @if($post->image_url)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-48 bg-slate-100 flex items-center justify-center text-slate-300 group-hover:bg-slate-200 transition-colors">
                            <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                    @endif
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-1 {{ $post->category === 'evento' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-brand-50 text-brand-700 border-brand-200' }} border text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                {{ $post->category }}
                            </span>
                            <span class="text-xs text-gray-400 font-semibold">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 leading-snug group-hover:text-brand-700 transition-colors">{{ $post->title }}</h4>
                        <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-1">{{ $post->content }}</p>

                        @if($post->category === 'evento' && $post->event_date)
                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center gap-2 text-xs font-bold text-gray-500">
                                <span class="material-symbols-outlined text-sm">event</span>
                                Fecha: {{ \Carbon\Carbon::parse($post->event_date)->translatedFormat('d F Y') }}
                            </div>
                        @else
                           <div class="mt-auto pt-4 border-t border-gray-100"></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center opacity-70">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-4 block mx-auto">sentiment_satisfied</span>
                    <p class="text-sm text-gray-500 font-medium">Por el momento no hay comunicados nuevos.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-dashboard-layout>