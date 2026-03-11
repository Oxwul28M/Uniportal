<x-dashboard-layout>
    <x-slot name="header_title">Mi Difusión</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-6">
            <div class="premium-card overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-slate-800">Mis Anuncios</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($posts as $post)
                        <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-all">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0">
                                    @if($post->image_url)
                                        <img src="{{ $post->image_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <span class="material-symbols-outlined text-2xl">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-base font-black text-slate-800">{{ $post->title }}</h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                        {{ $post->category }} • Publicado {{ $post->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('posts.toggle', $post->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $post->is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                        {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                                    </button>
                                </form>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 text-slate-300 hover:text-rose-600 transition-colors">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center opacity-30">
                            <span class="material-symbols-outlined text-4xl mb-4">campaign</span>
                            <p class="text-xs font-black uppercase tracking-widest">Aún no has publicado nada</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-6 border-t border-slate-50">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        <div class="lg:col-span-4" x-data="{ postCategory: '{{ old('category', 'noticia') }}' }">
            <div class="premium-card p-8 sticky top-24">
                <h4 class="text-lg font-black text-slate-800 mb-6">Nuevo Anuncio</h4>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                        <ul class="list-disc list-inside text-[10px] font-bold text-rose-600 uppercase tracking-tight">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Título</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="space-y-1" x-show="postCategory === 'evento'">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Fecha del
                            Evento</label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}"
                            :required="postCategory === 'evento'"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Categoría</label>
                        <select name="category" required x-model="postCategory"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-400 appearance-none">
                            <option value="noticia" {{ old('category') == 'noticia' ? 'selected' : '' }}>Noticia</option>
                            <option value="evento" {{ old('category') == 'evento' ? 'selected' : '' }}>Evento</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Imagen
                            (Adjuntar archivo)</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-bold outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Contenido</label>
                        <textarea name="content" rows="4" required
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-400 resize-none">{{ old('content') }}</textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white hover:text-white py-4 rounded-xl text-xs font-black shadow-xl shadow-blue-600/20 hover:bg-black transition-all uppercase tracking-widest">
                        PUBLICAR AHORA
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>