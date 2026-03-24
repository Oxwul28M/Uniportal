<x-dashboard-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Agenda Académica</h1>
            <p class="text-slate-500 font-medium">Gestiona tus eventos, evaluaciones y recordatorios por curso.</p>
        </div>
        <button @click="$dispatch('open-event-modal')" 
                class="bg-brand-800 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-brand-800/30 hover:bg-brand-900 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center gap-3">
            <span class="material-symbols-outlined text-sm">add</span>
            Nuevo Evento
        </button>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10" x-data="{ editingItem: null }">
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl p-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Eventos Programados</h3>
                    <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-4">Listado Cronológico</span>
                    </div>
                </div>

                <div class="space-y-6">
                    @forelse($items as $item)
                        @php
                            $colorMap = [
                                'exam' => ['bg' => 'rose-50', 'border' => 'rose-100', 'text' => 'rose-600', 'icon' => 'assignment_late'],
                                'deadline' => ['bg' => 'orange-50', 'border' => 'orange-100', 'text' => 'orange-600', 'icon' => 'timer'],
                                'meeting' => ['bg' => 'blue-50', 'border' => 'blue-100', 'text' => 'blue-600', 'icon' => 'groups'],
                                'holiday' => ['bg' => 'emerald-50', 'border' => 'emerald-100', 'text' => 'emerald-600', 'icon' => 'celebration'],
                                'generic' => ['bg' => 'slate-50', 'border' => 'slate-100', 'text' => 'slate-600', 'icon' => 'event'],
                            ];
                            $style = $colorMap[$item->type] ?? $colorMap['generic'];
                        @endphp
                        <div class="group relative flex gap-8 p-8 rounded-[2rem] bg-{{ $style['bg'] }}/30 border border-{{ $style['border'] }} hover:bg-{{ $style['bg'] }} transition-all duration-500">
                            <div class="size-20 bg-{{ $style['bg'] }} text-{{ $style['text'] }} rounded-[1.5rem] flex flex-col items-center justify-center shrink-0 shadow-sm transition-transform group-hover:scale-110">
                                <span class="text-2xl font-black leading-none mb-1">{{ $item->event_date->format('d') }}</span>
                                <span class="text-[9px] font-black uppercase tracking-widest">{{ $item->event_date->format('M') }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <span class="px-2 py-0.5 bg-{{ $style['text'] }} text-white text-[8px] font-black uppercase tracking-widest rounded-md">
                                        {{ strtoupper($item->type) }}
                                    </span>
                                    @foreach($item->courses as $c)
                                        <span class="px-2 py-0.5 bg-white border border-{{ $style['border'] }} text-{{ $style['text'] }} text-[8px] font-black uppercase tracking-widest rounded-md">
                                            {{ $c->code }}
                                        </span>
                                    @endforeach
                                    <h5 class="text-lg font-black text-slate-800 tracking-tight leading-tight w-full mt-1">{{ $item->title }}</h5>
                                </div>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ $item->description }}</p>
                            </div>
                            
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity absolute right-8 top-8">
                                <form action="{{ route('teacher.agenda.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este evento?')">
                                    @csrf @method('DELETE')
                                    <button class="size-8 rounded-lg bg-white border border-slate-100 text-rose-500 flex items-center justify-center hover:bg-rose-50 transition-colors shadow-sm">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <span class="material-symbols-outlined text-6xl text-slate-100 mb-4 block">event_busy</span>
                            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">No tienes eventos registrados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-10">
            <div class="bg-brand-800 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-brand-800/30 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 size-48 bg-white/10 rounded-full blur-[60px]"></div>
                <div class="size-16 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-md">
                    <span class="material-symbols-outlined text-4xl text-blue-200">calendar_today</span>
                </div>
                <h4 class="text-2xl font-black mb-4 tracking-tight leading-tight">Organiza tu Semestre</h4>
                <p class="text-blue-100/70 text-sm font-medium leading-relaxed mb-10">Crea eventos y selecciona qué cursos deben verlos. La información aparecerá automáticamente en el portal de tus estudiantes.</p>
                <button @click="$dispatch('open-event-modal')" class="w-full bg-white text-brand-800 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-blue-50 transition-all font-black">EMPEZAR AHORA</button>
            </div>
        </div>
    </div>

    <!-- Modal de Evento -->
    <div x-data="{ open: false }" 
         x-cloak 
         @open-event-modal.window="open = true"
         x-show="open" 
         class="fixed inset-0 z-[60] flex items-center justify-center p-6 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl relative overflow-hidden transform transition-all">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800">Cargar Nuevo Evento</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('teacher.agenda.store') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Título del Evento</label>
                        <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-brand-800/10 focus:border-brand-800 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Fecha</label>
                        <input type="date" name="event_date" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-brand-800/10 focus:border-brand-800 transition-all">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Categoría de Evento</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['exam' => 'Examen', 'deadline' => 'Corte', 'meeting' => 'Reunión', 'holiday' => 'Feriado', 'generic' => 'Otro'] as $val => $label)
                            <label class="relative">
                                <input type="radio" name="type" value="{{ $val }}" {{ $val == 'generic' ? 'checked' : '' }} class="peer absolute opacity-0">
                                <div class="border border-slate-100 rounded-xl p-3 text-center cursor-pointer transition-all peer-checked:bg-brand-50 peer-checked:border-brand-800 peer-checked:text-brand-800 hover:bg-slate-50">
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Visible para los cursos:</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($courses as $course)
                            <label class="relative">
                                <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" class="peer absolute opacity-0">
                                <div class="border border-slate-100 rounded-xl px-4 py-2 cursor-pointer transition-all peer-checked:bg-brand-800 peer-checked:border-brand-800 peer-checked:text-white hover:bg-slate-50">
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ $course->name }} ({{ $course->code }})</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold mt-3 italic">* Si no seleccionas ninguno, solo tú verás el evento.</p>
                </div>

                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Descripción / Detalles</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-brand-800/10 focus:border-brand-800 transition-all"></textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" @click="open = false" class="flex-1 px-8 py-4 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">CANCELAR</button>
                    <button type="submit" class="flex-1 px-8 py-4 bg-brand-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-brand-800/30 hover:bg-brand-900 transition-all">GUARDAR EVENTO</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>