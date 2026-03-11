<x-dashboard-layout>
    <x-slot name="header_title">Carga de Calificaciones</x-slot>

    <div class="premium-card overflow-hidden" x-data="{ loading: false }">
        <form action="{{ route('teacher.grades.store') }}" method="POST" @submit="loading = true">
            @csrf
            <input type="hidden" name="course_id" value="1">

            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Sección: ING-101 (Introducción a la Ing.)</h3>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Periodo 2026-I •
                        Actas abiertas hasta el 27 Feb</p>
                </div>
                <button type="submit" :disabled="loading"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-xs font-black shadow-lg shadow-indigo-600/20 hover:bg-black transition-all flex items-center gap-3 disabled:opacity-50">
                    <template x-if="loading">
                        <div class="spinner"></div>
                    </template>
                    <span x-text="loading ? 'GUARDANDO...' : 'GUARDAR CALIFICACIONES'"></span>
                </button>
            </div>

            <table class="w-full text-left">
                <thead class="bg-white border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Estudiante
                        </th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Calificación (0-20)</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($students as $student)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs">
                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">{{ $student->name }}</p>
                                        <p class="text-[10px] text-indigo-500 font-bold">MATRÍCULA: {{ $student->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                @php
                                    $currentGrade = $grades[$student->id] ?? null;
                                @endphp
                                <input type="number" step="0.1" max="20" min="0" name="grades[{{ $student->id }}]"
                                    value="{{ $currentGrade }}"
                                    class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-indigo-700 text-center focus:ring-2 focus:ring-indigo-400 transition-all outline-none">
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span
                                    class="px-3 py-1 text-[9px] font-black uppercase rounded-lg {{ ($currentGrade ?? 0) >= 10 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ ($currentGrade ?? 0) >= 10 ? 'Aprobado' : 'Reprobado' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</x-dashboard-layout>