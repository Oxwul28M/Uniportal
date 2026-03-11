<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Difusión Institucional</h1>
            <p class="text-gray-500 text-sm mt-1">Gestiona y publica anuncios, noticias y eventos para toda la comunidad educativa.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Posts List -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-700">campaign</span>
                        Mis Publicaciones
                    </h3>
                </div>
                
                <div class="divide-y divide-gray-50">
                    @forelse($posts as $post)
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 transition-colors group">
                            <div class="flex items-start gap-5">
                                <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-200 shadow-sm">
                                    @if($post->image_url)
                                        <img src="{{ $post->image_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <span class="material-symbols-outlined text-2xl">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-1 mt-1">
                                    <h5 class="text-base font-bold text-gray-900 leading-tight">{{ $post->title }}</h5>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $post->category === 'evento' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-brand-50 text-brand-700 border border-brand-200' }}">
                                            {{ $post->category }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            {{ $post->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <form action="{{ route('posts.toggle', $post->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        title="{{ $post->is_published ? 'Ocultar publicación' : 'Hacer pública' }}"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all border shadow-sm flex items-center gap-1.5 {{ $post->is_published ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                                        @if($post->is_published)
                                            <span class="material-symbols-outlined text-[14px]">visibility</span> Publicado
                                        @else
                                            <span class="material-symbols-outlined text-[14px]">visibility_off</span> Borrador
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar este anuncio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar" class="p-2 bg-white border border-gray-200 text-gray-400 rounded-lg hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-colors shadow-sm">
                                        <span class="material-symbols-outlined text-sm block">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <span class="material-symbols-outlined text-5xl mb-4 text-gray-300 block">campaign</span>
                            <p class="text-sm font-semibold text-gray-500">Aún no has creado ninguna publicación</p>
                            <p class="text-xs text-gray-400 mt-1">Usa el formulario para empezar a difundir información.</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        <!-- Create Post Form -->
        <div class="lg:col-span-4" x-data="{ postCategory: '{{ old('category', 'noticia') }}', loading: false }">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 lg:p-8 sticky top-24">
                <h4 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    </div>
                    Nuevo Anuncio
                </h4>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3 shadow-sm">
                        <span class="material-symbols-outlined text-lg shrink-0 mt-0.5">error</span>
                        <ul class="list-disc list-inside text-xs font-medium space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" @submit="loading = true">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Título</label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Ej: Inicio de clases"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                    </div>

                    <div class="space-y-1.5 hidden" :class="{ 'block': postCategory === 'evento', 'hidden': postCategory !== 'evento' }">
                        <label class="text-sm font-semibold text-gray-700">Fecha del Evento</label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}"
                            :required="postCategory === 'evento'"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Categoría</label>
                        <select name="category" required x-model="postCategory"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all">
                            <option value="noticia" {{ old('category') == 'noticia' ? 'selected' : '' }}>Noticia Institucional</option>
                            <option value="evento" {{ old('category') == 'evento' ? 'selected' : '' }}>Evento Especial</option>
                        </select>
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Imagen <span class="text-xs text-gray-400 font-normal">(Opcional)</span></label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all
                            file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Contenido</label>
                        <textarea name="content" rows="4" required placeholder="Escribe el cuerpo del anuncio..."
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all resize-none placeholder-gray-400">{{ old('content') }}</textarea>
                    </div>
                    
                    <button type="submit" :disabled="loading"
                        class="w-full mt-2 bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white hover:text-white py-3.5 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <template x-if="loading"><span class="animate-spin material-symbols-outlined text-sm">refresh</span></template>
                        <span class="material-symbols-outlined text-[18px]" x-show="!loading">send</span>
                        <span x-text="loading ? 'Publicando...' : 'Publicar Anuncio'"></span>
                    </button>
                    <p class="text-[11px] text-gray-500 text-center font-medium mt-3 px-2">
                        Los anuncios publicados serán visibles para todos los estudiantes y personal.
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout