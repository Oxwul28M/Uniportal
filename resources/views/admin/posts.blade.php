<x-dashboard-layout>
    <!-- Page Header -->
    <div class="mb-8 relative">
        <h1 class="text-2xl font-bold text-gray-900">Gestión de Publicaciones</h1>
        <p class="text-gray-500 text-sm mt-1">Crea y administra avisos institucionales y eventos para la comunidad.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Posts List -->
        <div class="lg:col-span-8 space-y-6">
            <div
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-brand-800">campaign</span>
                        <h3 class="text-lg font-bold text-gray-900">Publicaciones Recientes</h3>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                                    <div
                                        class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors gap-4 group">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="size-14 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-200 group-hover:border-brand-200 transition-colors">
                                                @if($post->image_url)
                                                    <img src="{{ $post->image_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <span class="material-symbols-outlined text-2xl">image</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-bold text-gray-900 leading-tight group-hover:text-brand-800 transition-colors">
                                                    {{ $post->title }}</h5>
                                                <div class="flex items-center gap-3 mt-1.5">
                                                    <span
                                                        class="text-xs font-semibold capitalize text-brand-700">{{ $post->category }}</span>
                                                    <span class="size-1 bg-gray-300 rounded-full"></span>
                                                    <span
                                                        class="text-xs text-gray-500 font-medium">
                                                        Por {{ explode(' ', $post->author->name)[0] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                                            <form action="{{ route('posts.toggle', $post->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border
                                                            {{ $post->is_published
                         ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                         : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                                    {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" x-data="{ confirming: false }" @submit.prevent="confirming = true">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>

                                                <!-- Delete Confirmation Modal -->
                                                <template x-teleport="body">
                                                    <div x-show="confirming" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm" x-cloak>
                                                        <div class="bg-white rounded-3xl w-full max-w-sm p-8 shadow-2xl relative" @click.away="confirming = false">
                                                            <div class="flex flex-col items-center justify-center text-center">
                                                                <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-4">
                                                                    <span class="material-symbols-outlined text-3xl">delete_forever</span>
                                                                </div>
                                                                <h4 class="text-xl font-bold text-gray-900 mb-2">Eliminar Publicación</h4>
                                                                <p class="text-sm text-gray-500 mb-8">¿Estás seguro de que deseas eliminar permanentemente "{{ $post->title }}"? Esta acción no se puede deshacer.</p>
                                                                
                                                                <div class="flex w-full gap-3">
                                                                    <button type="button" @click="confirming = false" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                                                                        Cancelar
                                                                    </button>
                                                                    <button type="button" @click="$el.closest('form').submit()" class="flex-1 px-4 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 shadow-md shadow-red-600/20 transition-all text-sm">
                                                                        Eliminar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </form>
                                        </div>
                                    </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-40">inbox</span>
                            <p class="text-sm font-semibold text-gray-600">No se encontraron publicaciones</p>
                        </div>
                    @endforelse
                </div>

                @if($posts->hasPages())
                    <div
                        class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Form -->
        <div class="lg:col-span-4" x-data="{ postCategory: '{{ old('category', 'noticia') }}' }">
            <div
                class="bg-white p-6 sticky top-24 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-brand-800">add_circle</span>
                    <h4 class="text-lg font-bold text-gray-900">Crear Publicación</h4>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" @submit="loading = true">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Título</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all text-sm shadow-inner placeholder-gray-400">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Categoría</label>
                        <select name="category" required x-model="postCategory"
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all text-sm shadow-inner">
                            <option value="noticia">Noticia</option>
                            <option value="evento">Evento</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Imagen</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Contenido</label>
                        <textarea name="content" rows="4" required
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all text-sm shadow-inner resize-none placeholder-gray-400">{{ old('content') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white py-3.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-3 shadow-md hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Publicar Ahora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>