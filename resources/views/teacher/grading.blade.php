<x-dashboard-layout>
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar de Navegación de Cursos -->
        <aside class="lg:w-80 shrink-0">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden sticky top-8">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tus Asignaturas</h3>
                    <p class="text-xs font-bold text-slate-500">Selecciona para calificar</p>
                </div>
                <div class="p-4 space-y-2">
                    @foreach($allCourses as $c)
                        <a href="{{ route('teacher.grading', ['course_id' => $c->id]) }}" 
                           class="flex items-center gap-4 p-4 rounded-2xl transition-all group {{ $course->id == $c->id ? 'bg-brand-800 text-white shadow-lg shadow-brand-800/20' : 'hover:bg-slate-50 text-slate-600' }}">
                            <div class="size-10 rounded-xl flex items-center justify-center font-black text-[10px] {{ $course->id == $c->id ? 'bg-white/20' : 'bg-slate-100 group-hover:bg-white transition-colors' }}">
                                {{ substr($c->name, 0, 2) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black truncate leading-tight">{{ $c->name }}</p>
                                <p class="text-[9px] font-bold opacity-60 uppercase tracking-widest mt-0.5">{{ $c->code }}</p>
                            </div>
                            @if($course->id == $c->id)
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Contenido Principal -->
        <main class="flex-1 min-w-0">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">Acta de Calificaciones</h1>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="px-3 py-1 bg-brand-50 text-brand-800 text-[10px] font-black uppercase rounded-lg border border-brand-100">{{ $course->name }}</span>
                        <span class="text-slate-300">•</span>
                        <p class="text-slate-500 font-medium text-sm">Sección: <span class="font-bold">A-1</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('teacher.grades.export', ['course_id' => $course->id]) }}" 
                       class="bg-white text-slate-600 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-slate-100 shadow-sm hover:border-brand-800 hover:text-brand-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Exportar Plantilla
                    </a>
                    <button @click="$dispatch('open-import-modal')" 
                            class="bg-white text-brand-800 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-brand-100 shadow-sm hover:bg-brand-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">upload</span>
                        Importar (CSV)
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden" x-data="{ loading: false }">
                <form action="{{ route('teacher.grades.store') }}" method="POST" @submit="loading = true">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="period" value="2026-I">

                    <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/10">
                        <div class="flex items-center gap-4">
                            <div class="size-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-brand-800 shadow-sm">
                                <span class="material-symbols-outlined">group</span>
                            </div>
                            <div>
                                <p class="text-lg font-black text-slate-800 tracking-tight">{{ $students->count() }} Estudiantes</p>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Listado de Inscritos</p>
                            </div>
                        </div>

                        <button type="submit" :disabled="loading"
                            class="bg-brand-800 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-brand-800/30 hover:bg-brand-900 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center gap-3 disabled:opacity-50 group">
                            <template x-if="loading">
                                <span class="size-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            </template>
                            <span x-text="loading ? 'PROCESANDO...' : 'GUARDAR CAMBIOS'"></span>
                            <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform" x-show="!loading">save</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Estudiante</th>
                                    <th class="px-4 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">Corte 1</th>
                                    <th class="px-4 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">Corte 2</th>
                                    <th class="px-4 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">Corte 3</th>
                                    <th class="px-4 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">Corte 4</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-center">Final (20)</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-right">Estatus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($students as $student)
                                    @php $gModel = $grades[$student->id] ?? null; @endphp
                                    <tr class="hover:bg-brand-50/20 transition-all group/row" 
                                        x-data="{ 
                                            e1: {{ $gModel->eval1 ?? 0 }}, 
                                            e2: {{ $gModel->eval2 ?? 0 }}, 
                                            e3: {{ $gModel->eval3 ?? 0 }}, 
                                            e4: {{ $gModel->eval4 ?? 0 }},
                                            get final() { 
                                                let avg = (parseFloat(this.e1) + parseFloat(this.e2) + parseFloat(this.e3) + parseFloat(this.e4)) / 4;
                                                return (avg / 5).toFixed(1);
                                            }
                                        }">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    <div class="size-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs">
                                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                                    </div>
                                                    <div class="absolute -right-1 -bottom-1 size-3 rounded-full border-2 border-white {{ $gModel ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-black text-slate-800 leading-tight">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #{{ $student->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        @for($i = 1; $i <= 4; $i++)
                                            <td class="px-2 py-6">
                                                <div class="flex justify-center">
                                                    <input type="number" step="1" max="100" min="0" 
                                                        name="grades[{{ $student->id }}][eval{{$i}}]"
                                                        x-model="e{{$i}}"
                                                        class="w-16 bg-slate-50 border border-slate-100 rounded-xl px-2 py-3 text-[11px] font-black text-slate-700 text-center focus:ring-4 focus:ring-brand-800/10 focus:border-brand-800 transition-all outline-none"
                                                        placeholder="0">
                                                </div>
                                            </td>
                                        @endfor
                                        <td class="px-8 py-6 text-center">
                                            <span class="text-lg font-black text-brand-800 font-mono tracking-tighter" x-text="final"></span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <span :class="final >= 10 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
                                                class="px-3 py-1 text-[8px] font-black uppercase rounded-full border border-current/20 tracking-widest shadow-sm">
                                                <span x-text="final >= 10 ? 'Aprobado' : 'Reprobado'"></span>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-8 py-20 text-center">
                                            <span class="material-symbols-outlined text-5xl text-slate-200 mb-4 block">person_off</span>
                                            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">No hay estudiantes inscritos en esta sección</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Modal de Importación (Sin cambios en lógica, solo cierre de diseño) -->
    <div x-data="{ open: false }" 
         x-cloak 
         @open-import-modal.window="open = true"
         x-show="open" 
         class="fixed inset-0 z-[60] flex items-center justify-center p-6 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg relative overflow-hidden transform transition-all">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800">Importar Calificaciones</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('teacher.grades.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="period" value="2026-I">
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Seleccionar Archivo (CSV)</label>
                    <div class="relative group">
                        <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="border-2 border-dashed border-slate-100 rounded-[2rem] p-10 text-center group-hover:bg-brand-50 group-hover:border-brand-200 transition-all">
                            <span class="material-symbols-outlined text-4xl text-slate-200 mb-2 group-hover:text-brand-800">upload_file</span>
                            <p class="text-sm font-bold text-slate-500">Arrastra tu archivo o haz clic aquí</p>
                            <p class="text-[10px] text-slate-400 mt-2 uppercase tracking-tight font-bold">Importante: El archivo debe tener el ID del estudiante en la primera columna.</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" @click="open = false" class="flex-1 px-8 py-4 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="flex-1 px-8 py-4 bg-brand-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-brand-800/30 hover:bg-brand-900 transition-all">Subir y Procesar</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>