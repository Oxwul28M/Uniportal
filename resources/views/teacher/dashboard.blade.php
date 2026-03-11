<x-dashboard-layout>
    <!-- Teacher Header -->
    <div class="relative rounded-[2.5rem] p-10 mb-10 overflow-hidden shadow-2xl group border border-white/20 bg-gradient-to-br from-brand-900 via-brand-800 to-blue-900 text-white">
        <div class="absolute -right-20 -top-20 size-96 bg-blue-400/20 rounded-full blur-[100px] group-hover:bg-blue-300/30 transition-all duration-1000"></div>
        <div class="absolute -left-20 -bottom-20 size-96 bg-brand-400/10 rounded-full blur-[100px] group-hover:bg-brand-300/20 transition-all duration-1000"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Periodo 2026-I Activo</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 leading-tight">
                    ¡Buen día, Prof. <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">{{ explode(' ', Auth::user()->name)[0] }}</span>! 🍎
                </h1>
                <p class="text-blue-100/80 text-lg max-w-xl font-medium leading-relaxed">
                    Tu portal académico está actualizado. Revisa tus secciones, gestiona calificaciones y mantente al día con tu agenda académica.
                </p>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-[2rem] flex items-center gap-8 shadow-inner">
                    <div class="text-center px-2">
                        <p class="text-[10px] text-blue-200 font-black uppercase tracking-widest mb-1.5 opacity-70">Secciones</p>
                        <p class="text-4xl font-black tracking-tighter">{{ $sections }}</p>
                    </div>
                    <div class="w-px h-12 bg-white/10"></div>
                    <div class="text-center px-2">
                        <p class="text-[10px] text-blue-200 font-black uppercase tracking-widest mb-1.5 opacity-70">Alumnos</p>
                        <p class="text-4xl font-black tracking-tighter">{{ $totalStudents }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-10">
        <!-- Main Actions & Courses -->
        <div class="lg:col-span-8 space-y-10">
            <div>
                <div class="flex items-center justify-between mb-8 px-2">
                    <h3 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                        <span class="size-10 bg-brand-800 rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand-800/20">
                            <span class="material-symbols-outlined text-xl">auto_stories</span>
                        </span>
                        Mis Secciones Activas
                    </h3>
                    <a href="{{ route('teacher.courses.index') }}" class="text-sm font-bold text-brand-800 hover:text-blue-600 transition-colors flex items-center gap-2">
                        Ver todas <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($courses->take(4) as $course)
                        <div class="group bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 size-24 bg-brand-50 rounded-full blur-2xl group-hover:bg-brand-100/50 transition-colors"></div>
                            
                            <div class="flex justify-between items-start mb-6 relative z-10">
                                <div class="size-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:bg-brand-800 group-hover:text-white transition-all duration-500 shadow-sm">
                                    <span class="material-symbols-outlined">class</span>
                                </div>
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase rounded-lg tracking-wider">Sección {{ $course->section }}</span>
                            </div>
                            
                            <h4 class="text-lg font-black text-slate-800 mb-1 group-hover:text-brand-800 transition-colors tracking-tight">{{ $course->name }}</h4>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">{{ $course->code }}</p>
                            
                            <div class="flex items-center justify-between pt-6 border-t border-slate-50 relative z-10">
                                <div class="flex items-center gap-2 text-slate-500">
                                    <span class="material-symbols-outlined text-sm">groups</span>
                                    <span class="text-xs font-bold">{{ $course->students_count }} Estudiantes</span>
                                </div>
                                <a href="{{ route('teacher.grading.index', ['course_id' => $course->id]) }}" class="size-10 bg-brand-50 text-brand-800 rounded-xl flex items-center justify-center hover:bg-brand-800 hover:text-white transition-all group/btn shadow-sm">
                                    <span class="material-symbols-outlined text-xl group-hover/btn:rotate-12 transition-transform">grade</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Quick Link / Shortcut Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('teacher.agenda.index') }}" class="p-10 bg-brand-950 rounded-[2.5rem] shadow-xl hover:shadow-brand-950/20 transition-all group relative overflow-hidden">
                    <div class="absolute right-0 top-0 size-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors"></div>
                    <div class="relative z-10">
                        <span class="material-symbols-outlined text-4xl text-blue-400 mb-6 drop-shadow-[0_0_15px_rgba(96,165,250,0.5)]">calendar_month</span>
                        <h4 class="text-2xl font-black text-white mb-2 tracking-tight">Agenda Académica</h4>
                        <p class="text-blue-100/60 text-sm font-medium">Revisa las fechas de exámenes, feriados y cierres de actas.</p>
                    </div>
                </a>
                
                <div class="p-10 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 group relative overflow-hidden flex flex-col justify-center">
                    <div class="flex items-center gap-6">
                        <div class="size-16 bg-blue-50 text-blue-600 rounded-[1.5rem] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-3xl">info</span>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-slate-800 tracking-tight">Ayuda & Soporte</h4>
                            <p class="text-slate-400 text-sm font-medium">¿Necesitas asistencia con las actas? Contacta a control de estudios.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Stats -->
        <div class="lg:col-span-4 space-y-10">
            <!-- Recent Activity -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm">
                <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-brand-800">history</span>
                    Actas Recientes
                </h3>
                
                <div class="space-y-6">
                    @forelse($recentGrades as $grade)
                        <div class="flex gap-4 group">
                            <div class="size-10 rounded-xl bg-slate-50 flex items-center justify-center font-black text-slate-400 text-xs shrink-0 group-hover:bg-brand-50 group-hover:text-brand-800 transition-colors">
                                {{ strtoupper(substr($grade->student->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-black text-slate-800 truncate">{{ $grade->student->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $grade->course->code }}</span>
                                    <span class="size-1 bg-slate-200 rounded-full"></span>
                                    <span class="text-[10px] font-black {{ $grade->grade >= 10 ? 'text-emerald-500' : 'text-rose-500' }}">{{ number_format($grade->grade, 1) }} PTOS</span>
                                </div>
                                <p class="text-[9px] text-slate-300 font-bold mt-1 tracking-tighter">{{ $grade->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-4xl text-slate-100 mb-2">history_toggle_off</span>
                            <p class="text-xs font-bold text-slate-400">No hay actividad reciente.</p>
                        </div>
                    @endforelse
                </div>
                
                <button class="w-full mt-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-brand-800 transition-colors border-t border-slate-50">
                    VER HISTORIAL COMPLETO
                </button>
            </div>

            <!-- Promotion Card -->
            <div class="rounded-[2.5rem] bg-gradient-to-br from-blue-600 to-brand-800 p-8 text-white shadow-xl shadow-blue-600/20 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 size-40 bg-white/10 rounded-full blur-3xl"></div>
                <h4 class="text-xl font-black mb-4 tracking-tight">Carga de Notas v2.0</h4>
                <p class="text-blue-100/70 text-sm font-medium leading-relaxed mb-8">Ahora puedes importar calificaciones directamente desde archivos Excel. ¡Ahorra tiempo!</p>
                <button class="px-6 py-3 bg-white text-brand-800 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-50 transition-colors shadow-lg">
                    EXPLORAR FUNCIÓN
                </button>
            </div>
        </div>
    </div>
</x-dashboard-layout>