<x-dashboard-layout>
    <x-slot name="header_title">Calendario Académico</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="premium-card p-8 bg-white">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-xl font-black text-slate-800">Fechas Importantes — 2026-I</h3>
                    <div class="flex items-center gap-4">
                        <button @click="$dispatch('notify', {message: 'Funcionalidad de calendario anterior en desarrollo.', type: 'info'})" class="p-2 bg-slate-50 rounded-lg"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
                        <span class="text-sm font-black text-slate-800 uppercase tracking-widest">Febrero</span>
                        <button @click="$dispatch('notify', {message: 'Funcionalidad de mes siguiente en desarrollo.', type: 'info'})" class="p-2 bg-slate-50 rounded-lg"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div
                        class="p-6 bg-orange-50/50 border border-orange-100 rounded-[2rem] flex items-center justify-between group hover:bg-orange-50 transition-all">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex flex-col items-center justify-center">
                                <span class="text-lg font-black leading-none">27</span>
                                <span class="text-[9px] font-black uppercase tracking-widest">FEB</span>
                            </div>
                            <div>
                                <h5 class="text-sm font-black text-slate-800 uppercase">Cierre de Actas Parciales</h5>
                                <p class="text-xs text-slate-400 font-medium mt-1">Fecha límite para la carga de notas
                                    del primer parcial.</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-orange-400 opacity-0 group-hover:opacity-100 transition-opacity">warning</span>
                    </div>

                    <div
                        class="p-6 bg-blue-50/50 border border-blue-100 rounded-[2rem] flex items-center justify-between group hover:bg-blue-50 transition-all">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex flex-col items-center justify-center">
                                <span class="text-lg font-black leading-none">15</span>
                                <span class="text-[9px] font-black uppercase tracking-widest">MAR</span>
                            </div>
                            <div>
                                <h5 class="text-sm font-black text-slate-800 uppercase">Semana de Proyectos</h5>
                                <p class="text-xs text-slate-400 font-medium mt-1">Inicio de defensas y tutorías
                                    especializadas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="premium-card p-8 bg-indigo-600 text-white shadow-2xl shadow-indigo-600/20">
                <span class="material-symbols-outlined text-4xl mb-6 text-indigo-200">info</span>
                <h4 class="text-lg font-black mb-2">Recordatorio</h4>
                <p class="text-indigo-100 text-sm leading-relaxed mb-8">El sistema de tutorías estará fuera de servicio
                    el domingo por mantenimiento programado.</p>
                <button @click="$dispatch('notify', {message: 'Descargando Boletín Técnico PDF...', type: 'info'})"
                    class="w-full bg-white text-indigo-600 py-4 rounded-xl text-xs font-black uppercase tracking-widest">Ver
                    Boletín Técnico</button>
            </div>
        </div>
    </div>
</x-dashboard-layout>