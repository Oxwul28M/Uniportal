<x-dashboard-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-5">
            <a href="{{ route('teacher.courses.index') }}" class="size-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 hover:text-brand-800 hover:border-brand-800 transition-all shadow-sm group">
                <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">Acta de Calificaciones</h1>
                <p class="text-slate-500 font-medium">Sección: <span class="text-brand-800 font-bold">{{ $course->name }}</span> ({{ $course->code }})</p>
            </div>
        </div>

        <div class="bg-brand-50 border border-brand-100 px-6 py-3 rounded-2xl flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-[10px] font-black text-brand-900 uppercase tracking-widest">Actas Abiertas — 2026-I</span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden" x-data="{ loading: false }">
        <form action="{{ route('teacher.grades.store') }}" method="POST" @submit="loading = true">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="period" value="2026-I">

            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/30">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-brand-800 shadow-sm">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div>
                        <p class="text-lg font-black text-slate-800 tracking-tight">{{ $students->count() }} Estudiantes</p>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Listado oficial de cursantes</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" :disabled="loading"
                        class="bg-brand-800 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-brand-800/30 hover:bg-brand-900 hover:-translate-y-0.5 transition-all flex items-center gap-3 disabled:opacity-50 group">
                        <template x-if="loading">
                            <span class="size-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        </template>
                        <span x-text="loading ? 'PROCESANDO...' : 'GUARDAR CALIFICACIONES'"></span>
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform" x-show="!loading">save</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Estudiante</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">ID / Matrícula</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">Puntuación (0-20)</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-right">Estatus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($students as $student)
                            @php
                                $val = $grades[$student->id] ?? null;
                                $isPassing = $val !== null && $val >= 10;
                            @endphp
                            <tr class="hover:bg-brand-50/20 transition-all group/row" x-data="{ grade: {{ $val ?? 'null' }} }">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="relative">
                                            <div class="size-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border border-white shadow-sm flex items-center justify-center font-black text-slate-500 text-sm group-hover/row:scale-110 group-hover/row:rotate-3 transition-all duration-500">
                                                {{ strtoupper(substr($student->name, 0, 2)) }}
                                            </div>
                                            <div class="absolute -right-1 -bottom-1 size-4 rounded-full border-2 border-white {{ $val !== null ? ($isPassing ? 'bg-emerald-500' : 'bg-rose-500') : 'bg-slate-300' }}"></div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-800 leading-tight tracking-tight">{{ $student->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-center">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase rounded-lg">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex items-center justify-center">
                                        <div class="relative group/input max-w-[100px]">
                                            <input type="number" step="0.5" max="20" min="0" 
                                                name="grades[{{ $student->id }}]"
                                                x-model="grade"
                                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-4 text-sm font-black text-slate-800 text-center focus:ring-4 focus:ring-brand-800/10 focus:border-brand-800 transition-all outline-none"
                                                placeholder="-">
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <template x-if="grade !== null && grade !== ''">
                                        <span :class="grade >= 10 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'"
                                            class="px-4 py-1.5 text-[9px] font-black uppercase rounded-full border tracking-widest shadow-sm">
                                            <span x-text="grade >= 10 ? 'Aprobado' : 'Reprobado'"></span>
                                        </span>
                                    </template>
                                    <template x-if="grade === null || grade === ''">
                                        <span class="px-4 py-1.5 text-[9px] font-black uppercase rounded-full border border-slate-100 bg-slate-50 text-slate-400 tracking-widest">
                                            Pendiente
                                        </span>
                                    </template>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($students->isEmpty())
                <div class="p-20 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-200 mb-4 block">person_off</span>
                    <p class="text-slate-400 font-medium">No hay estudiantes inscritos en esta sección.</p>
                </div>
            @endif
        </form>
    </div>
</x-dashboard-layout>