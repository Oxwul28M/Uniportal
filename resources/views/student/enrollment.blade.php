<x-dashboard-layout>
    <x-slot name="header_title">Proceso de Inscripción</x-slot>
    <x-slot name="header_subtitle">Inscríbete en las materias del periodo 2026-I</x-slot>

    <div class="max-w-4xl mx-auto space-y-8" x-data="{ 
        step: 1, 
        selectedSubjects: [],
        toggleSubject(id) {
            if (this.selectedSubjects.includes(id)) {
                this.selectedSubjects = this.selectedSubjects.filter(i => i !== id);
            } else {
                this.selectedSubjects.push(id);
            }
        }
    }">
        <!-- Stepper -->
        <div class="flex items-center justify-between px-10 relative">
            <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -z-10 -translate-y-1/2"></div>
            <div class="absolute top-1/2 left-0 h-1 bg-blue-600 -z-10 -translate-y-1/2 transition-all duration-500"
                :style="'width: ' + ((step-1)*50) + '%'"></div>

            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm transition-all duration-300"
                    :class="step >= 1 ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-slate-200 text-slate-400'">
                    1</div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Materias</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm transition-all duration-300"
                    :class="step >= 2 ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-slate-200 text-slate-400'">
                    2</div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Horario</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm transition-all duration-300"
                    :class="step >= 3 ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-slate-200 text-slate-400'">
                    3</div>
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Finalizar</span>
            </div>
        </div>

        <!-- Step 1: Subjects Selection -->
        <div x-show="step === 1" x-transition class="space-y-6">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-xl font-bold text-slate-800">Oferta Académica</h3>
                <span class="text-xs font-bold text-slate-400"
                    x-text="selectedSubjects.length + ' materias seleccionadas'"></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $courses = DB::table('courses')->get();
                @endphp
                @foreach($courses as $course)
                    <button @click="toggleSubject({{ $course->id }})"
                        class="premium-card p-6 flex items-center justify-between group transition-all text-left"
                        :class="selectedSubjects.includes({{ $course->id }}) ? 'border-blue-600 ring-1 ring-blue-600' : ''">
                        <div>
                            <h5 class="text-sm font-black text-slate-800">{{ $course->name }}</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $course->code }} •
                                Prof. {{ $course->teacher_name }}</p>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                            :class="selectedSubjects.includes({{ $course->id }}) ? 'bg-blue-600 border-blue-600 text-white' : 'border-slate-200'">
                            <span x-show="selectedSubjects.includes({{ $course->id }})" class="material-symbols-outlined text-sm">check</span>
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="flex justify-end pt-6">
                <button @click="step = 2" :disabled="selectedSubjects.length === 0"
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-sm font-black shadow-xl shadow-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    CONTINUAR
                </button>
            </div>
        </div>

        <!-- Step 2: Confirmation placeholder -->
        <div x-show="step === 2" x-transition class="text-center py-20">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-4xl">calendar_today</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">¡Casi listo!</h3>
            <p class="text-slate-400 text-sm max-w-sm mx-auto mb-10">Tu horario se generará automáticamente según las
                materias seleccionadas.</p>
            <div class="flex gap-4 justify-center">
                <button @click="step = 1"
                    class="px-8 py-4 text-sm font-black text-slate-400 hover:text-slate-800 transition-all uppercase tracking-widest">Atrás</button>
                <button @click="step = 3"
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-sm font-black shadow-xl shadow-blue-600/20 transition-all">CONFIRMAR
                    INSCRIPCIÓN</button>
            </div>
        </div>

        <!-- Step 3: Success -->
        <div x-show="step === 3" x-transition class="text-center py-20">
            <div
                class="w-24 h-24 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <span class="material-symbols-outlined text-5xl">celebration</span>
            </div>
            <h3 class="text-3xl font-black text-slate-800 mb-4">Inscripción Exitosa</h3>
            <p class="text-slate-400 text-sm mb-12">Has formalizado tu semestre 2026-I. Ya puedes consultar tu horario
                actualizado.</p>
            <a href="{{ route('student.schedule') }}"
                class="bg-[#1e293b] text-white px-10 py-4 rounded-2xl text-sm font-black shadow-xl shadow-slate-900/10 hover:bg-black transition-all inline-block uppercase tracking-widest">
                VER MI HORARIO
            </a>
        </div>
    </div>
</x-dashboard-layout>