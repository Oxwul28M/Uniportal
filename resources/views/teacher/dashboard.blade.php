<x-dashboard-layout>
    <!-- Teacher Header -->
    <div class="glass rounded-xl p-10 mb-8 relative overflow-hidden shadow-2xl border border-primary/20 group">
        <div
            class="absolute -right-10 -top-10 size-80 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-700">
        </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-end gap-8">
            <div class="flex-1">
                <h1 class="text-4xl font-bold tracking-tight mb-4 text-slate-900 dark:text-slate-100">
                    ¡Buenos días, Prof. {{ Auth::user()->name }}! 📚
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-xl font-medium leading-relaxed">
                    Gestiona tus actas de calificaciones y agenda académica desde un solo lugar.
                </p>
            </div>

            <div class="flex items-center gap-6 glass p-6 rounded-2xl border border-primary/20 bg-primary/5 shrink-0">
                <div class="text-center px-4">
                    <p class="text-[10px] text-primary font-black uppercase tracking-widest mb-1">Secciones</p>
                    <p class="text-2xl font-bold tracking-tighter">{{ $sections }}</p>
                </div>
                <div class="w-px h-10 bg-primary/20"></div>
                <div class="text-center px-4">
                    <p class="text-[10px] text-primary font-black uppercase tracking-widest mb-1">Alumnos</p>
                    <p class="text-2xl font-bold tracking-tighter">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Dashboard Summary Grid -->
        <div class="lg:col-span-8 space-y-6">
            <h3 class="text-xl font-bold flex items-center gap-3 px-2">
                <span class="material-symbols-outlined text-primary">dashboard</span>
                Gestión Rápida
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('teacher.courses.index') }}"
                    class="glass p-8 group hover:bg-primary/5 transition-all rounded-xl border border-primary/10 shadow-lg">
                    <div
                        class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">menu_book</span>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-2">Docencia</h4>
                    <p class="text-xs text-slate-400 font-medium">Revisa tus cursos asignados y listados de alumnos.</p>
                </a>
                <a href="{{ route('teacher.grading.index') }}"
                    class="glass p-8 group hover:bg-primary/5 transition-all rounded-xl border border-primary/10 shadow-lg">
                    <div
                        class="w-12 h-12 bg-emerald-500/10 text-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">grade</span>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-2">Carga de Notas</h4>
                    <p class="text-xs text-slate-400 font-medium">Acceso directo a la carga de actas del periodo actual.
                    </p>
                </a>
            </div>
        </div>

        <!-- Events List -->
        <div class="lg:col-span-4 space-y-6">
            <h3 class="text-xl font-bold flex items-center gap-3 px-2">
                <span class="material-symbols-outlined text-primary">event_upcoming</span>
                Próximos Eventos
            </h3>
            <div class="glass p-6 rounded-xl border border-primary/10 shadow-lg relative overflow-hidden">
                <div class="absolute -right-4 -top-4 size-20 bg-primary/5 rounded-full blur-xl"></div>
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-orange-400 text-sm">notifications_active</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Cierre de Notas</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Mañana, 23:59 PM
                            </p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('teacher.agenda.index') }}"
                    class="w-full mt-8 block text-center py-3 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary/20 transition-all border border-primary/10">
                    VER AGENDA COMPLETA
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>