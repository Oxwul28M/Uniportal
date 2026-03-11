<x-dashboard-layout>
    <x-slot name="header_title">Mis Secciones</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="premium-card p-8 group hover:border-indigo-600 transition-all">
                <div class="flex justify-between items-start mb-6">
                    <div
                        class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <span class="material-symbols-outlined">menu_book</span>
                    </div>
                    <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-black uppercase rounded-lg">ID:
                        {{ $course->id }}</span>
                </div>
                <h4 class="text-xl font-black text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors">
                    {{ $course->name }}</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">{{ $course->code }} — Secc. A</p>

                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                    <div class="flex -space-x-3">
                        <div
                            class="w-8 h-8 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">
                            J</div>
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200"></div>
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-300"></div>
                    </div>
                    <a href="{{ route('teacher.grading.index') }}"
                        class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:underline">Gestionar
                        Notas</a>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard-layout>