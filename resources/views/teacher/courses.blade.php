<x-dashboard-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Mis Cursos y Secciones</h1>
            <p class="text-slate-500 font-medium">Gestiona tu carga académica del periodo actual 2026-I.</p>
        </div>
        
        <div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
            <button class="px-5 py-2.5 bg-brand-800 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-brand-800/20 hover:bg-brand-900 transition-all">Docencia Actual</button>
            <button class="px-5 py-2.5 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all">Historial</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($courses as $course)
            <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col">
                <div class="p-8 flex-1">
                    <div class="flex justify-between items-start mb-8">
                        <div class="size-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-brand-800 group-hover:text-white transition-all duration-500 shadow-sm">
                            <span class="material-symbols-outlined text-3xl">menu_book</span>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-3 py-1 bg-brand-50 text-brand-800 text-[10px] font-black uppercase rounded-lg tracking-wider">Sección {{ $course->section }}</span>
                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">ID: #{{ str_pad($course->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-slate-800 mb-2 group-hover:text-brand-800 transition-colors tracking-tight leading-tight">
                        {{ $course->name }}
                    </h4>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-8">{{ $course->code }}</p>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Estudiantes</span>
                            <span class="text-sm font-black text-slate-800">{{ $course->students_count }} / {{ $course->max_students }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                    <div class="flex -space-x-3">
                        @for($i = 0; $i < min(3, $course->students_count); $i++)
                            <div class="size-8 rounded-full border-2 border-white bg-brand-100 flex items-center justify-center text-[10px] font-black text-brand-800 uppercase">
                                {{ chr(65 + $i) }}
                            </div>
                        @endfor
                        @if($course->students_count > 3)
                            <div class="size-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[8px] font-black text-slate-500">
                                +{{ $course->students_count - 3 }}
                            </div>
                        @endif
                    </div>
                    
                    <a href="{{ route('teacher.grading.index', ['course_id' => $course->id]) }}" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-brand-800 hover:text-blue-600 transition-colors group/link">
                        Gestionar Notas 
                        <span class="material-symbols-outlined text-sm group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-[2.5rem] border border-dashed border-slate-200 p-20 text-center">
            <div class="size-20 bg-slate-50 text-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-5xl">inventory_2</span>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Sin cursos asignados</h3>
            <p class="text-slate-400 font-medium max-w-sm mx-auto">No tienes secciones asignadas para el periodo actual. Contacta a coordinación académica.</p>
        </div>
    @endif
</x-dashboard-layout>