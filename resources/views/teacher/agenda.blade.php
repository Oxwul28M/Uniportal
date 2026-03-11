<x-dashboard-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Agenda Académica</h1>
        <p class="text-slate-500 font-medium">Cronograma de actividades y fechas clave — Periodo 2026-I.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl p-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Próximos Eventos</h3>
                    <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                        <button class="size-10 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-brand-800 transition-all">
                            <span class="material-symbols-outlined text-xl">chevron_left</span>
                        </button>
                        <span class="text-xs font-black text-slate-800 uppercase tracking-widest px-4">Marzo 2026</span>
                        <button class="size-10 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-brand-800 transition-all">
                            <span class="material-symbols-outlined text-xl">chevron_right</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="group relative flex gap-8 p-8 rounded-[2rem] bg-orange-50/30 border border-orange-100 hover:bg-orange-50 transition-all duration-500">
                        <div class="size-20 bg-orange-100 text-orange-600 rounded-[1.5rem] flex flex-col items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110">
                            <span class="text-2xl font-black leading-none mb-1">27</span>
                            <span class="text-[9px] font-black uppercase tracking-widest">FEB</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2 py-0.5 bg-orange-600 text-white text-[8px] font-black uppercase tracking-widest rounded-md">Urgente</span>
                                <h5 class="text-lg font-black text-slate-800 tracking-tight leading-tight">Cierre de Actas Parciales</h5>
                            </div>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Fecha límite improrrogable para la carga y validación de las notas correspondientes al primer parcial del semestre.</p>
                        </div>
                        <span class="material-symbols-outlined text-orange-400 opacity-20 group-hover:opacity-100 transition-opacity absolute right-8 top-8">warning</span>
                    </div>

                    <div class="group relative flex gap-8 p-8 rounded-[2rem] bg-brand-50/30 border border-brand-100 hover:bg-brand-50 transition-all duration-500">
                        <div class="size-20 bg-brand-100 text-brand-800 rounded-[1.5rem] flex flex-col items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110">
                            <span class="text-2xl font-black leading-none mb-1">15</span>
                            <span class="text-[9px] font-black uppercase tracking-widest">MAR</span>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-lg font-black text-slate-800 tracking-tight leading-tight mb-2">Semana de Proyectos I</h5>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Inicio de defensas y tutorías especializadas para los proyectos de semestre. Revisar cronograma por sección.</p>
                        </div>
                    </div>

                    <div class="group relative flex gap-8 p-8 rounded-[2rem] bg-slate-50/30 border border-slate-100 hover:bg-slate-50 transition-all duration-500">
                        <div class="size-20 bg-slate-100 text-slate-600 rounded-[1.5rem] flex flex-col items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110">
                            <span class="text-2xl font-black leading-none mb-1">22</span>
                            <span class="text-[9px] font-black uppercase tracking-widest">MAR</span>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-lg font-black text-slate-800 tracking-tight leading-tight mb-2">Feriado Académico</h5>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Día no laborable. Todas las actividades académicas presenciales y virtuales se reanudarán el día hábil siguiente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-10">
            <div class="bg-brand-800 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-brand-800/30 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 size-48 bg-white/10 rounded-full blur-[60px]"></div>
                <div class="size-16 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-md">
                    <span class="material-symbols-outlined text-4xl text-blue-200">notifications_active</span>
                </div>
                <h4 class="text-2xl font-black mb-4 tracking-tight leading-tight">Recordatorio de Seguridad</h4>
                <p class="text-blue-100/70 text-sm font-medium leading-relaxed mb-10">Recuerda cerrar tu sesión si accedes desde equipos compartidos en la sala de profesores. El sistema se cerrará automáticamente tras 20 min de inactividad.</p>
                <button class="w-full bg-white text-brand-800 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-blue-50 transition-all">Entendido</button>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm group">
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Enlaces Útiles</h4>
                <div class="space-y-4">
                    <a href="#" class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-brand-50 transition-all group/link">
                       <div class="flex items-center gap-3">
                           <span class="material-symbols-outlined text-slate-400 group-hover/link:text-brand-800">description</span>
                           <span class="text-sm font-black text-slate-700 group-hover/link:text-brand-800">Manual del Docente</span>
                       </div>
                       <span class="material-symbols-outlined text-slate-300 text-sm group-hover/link:translate-x-1 transition-transform">open_in_new</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>