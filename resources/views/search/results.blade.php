<x-dashboard-layout>
    <div class="mb-8 p-10 bg-white rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Resultados para: "{{ $query }}"</h1>
            <p class="text-slate-500 font-medium italic">Se encontraron un total de {{ $results['users']->count() + $results['courses']->count() + $results['posts']->count() + $results['agenda']->count() }} coincidencias.</p>
        </div>
        <div class="absolute -right-20 -top-20 size-64 bg-brand-50 rounded-full blur-3xl opacity-50"></div>
    </div>

    <div class="space-y-12 pb-20">
        @if($results['users']->count() > 0)
            <section>
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="size-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Usuarios</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($results['users'] as $user)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="size-12 rounded-xl overflow-hidden shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1e3a8a&color=fff" class="size-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 truncate">{{ $user->name }}</h3>
                                <p class="text-xs text-slate-500 uppercase font-black tracking-widest">{{ $user->role }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($results['courses']->count() > 0)
            <section>
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="size-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">menu_book</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Cursos / Materias</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($results['courses'] as $course)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2.5 py-1 bg-brand-50 text-brand-700 text-[10px] font-black uppercase rounded-lg border border-brand-100">{{ $course->code }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sección {{ $course->section }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $course->name }}</h3>
                            <p class="text-xs text-slate-500 font-medium">Profesor: {{ $course->teacher_name ?? ($course->teacher->name ?? 'N/A') }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($results['posts']->count() > 0)
            <section>
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="size-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">news</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Comunicados</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($results['posts'] as $post)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[9px] font-black uppercase rounded-md border border-slate-100">{{ $post->category }}</span>
                                <span class="text-[9px] font-medium text-slate-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-2 truncate">{{ $post->title }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2">{{ $post->content }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($results['agenda']->count() > 0)
            <section>
                <div class="flex items-center gap-4 mb-6 px-2">
                    <div class="size-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">event</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Agenda Académica</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($results['agenda'] as $item)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex gap-4 hover:shadow-md transition-shadow">
                            <div class="size-14 bg-slate-50 rounded-xl flex flex-col items-center justify-center shrink-0 border border-slate-100">
                                <span class="text-lg font-bold text-slate-800 leading-none">{{ $item->event_date->format('d') }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $item->event_date->format('M') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 truncate">{{ $item->title }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $item->description }}</p>
                                <div class="mt-2 flex gap-1">
                                    @foreach($item->courses as $c)
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-1 border border-slate-100 rounded">{{ $c->code }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($results['users']->isEmpty() && $results['courses']->isEmpty() && $results['posts']->isEmpty() && $results['agenda']->isEmpty())
            <div class="py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                <div class="size-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-4xl">search_off</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">No encontramos nada</h2>
                <p class="text-slate-500 font-medium">Intenta con otros términos o palabras clave.</p>
                <a href="{{ url()->previous() }}" class="mt-8 inline-flex items-center gap-2 text-brand-800 font-bold hover:underline">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    Volver atrás
                </a>
            </div>
        @endif
    </div>
</x-dashboard-layout>
