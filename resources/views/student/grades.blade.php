<x-dashboard-layout>
    <x-slot name="header_title">Mi Record Académico</x-slot>
    <x-slot name="header_subtitle">Calificaciones detalladas por asignatura y periodo</x-slot>

    <div class="space-y-8">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                Notas del Periodo Actual
            </h3>
            <span
                class="bg-blue-600 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Periodo:
                2026-I</span>
        </div>

        <div class="premium-card overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asignatura
                        </th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Profesor
                        </th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Nota Final</th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Resultado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($grades as $grade)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <p class="text-sm font-black text-slate-800">{{ $grade->course_name }}</p>
                                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">{{ $grade->code }}
                                </p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs font-bold text-slate-500">{{ $grade->teacher_name }}</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span
                                    class="text-2xl font-black text-blue-700 tracking-tighter">{{ number_format($grade->grade, 1) }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span
                                    class="px-3 py-1 text-[9px] font-black uppercase rounded-lg {{ $grade->grade >= 10 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $grade->grade >= 10 ? 'Aprobado' : 'Reprobado' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-30">
                                    <span class="material-symbols-outlined text-5xl">description</span>
                                    <p class="text-sm font-bold uppercase tracking-widest">No hay notas cargadas todavía</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Historical stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="premium-card p-6 flex items-center gap-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                    <span class="material-symbols-outlined text-3xl">stacked_line_chart</span>
                </div>
                <div>
                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Promedio General
                    </h5>
                    <p class="text-3xl font-black text-slate-800 tracking-tighter">16.4</p>
                </div>
            </div>
            <div class="premium-card p-6 flex items-center gap-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                    <span class="material-symbols-outlined text-3xl">check_circle</span>
                </div>
                <div>
                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">UC Aprobadas</h5>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-black text-slate-800 tracking-tighter">142</p>
                        <span class="text-xs font-bold text-slate-400">/ 180</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>