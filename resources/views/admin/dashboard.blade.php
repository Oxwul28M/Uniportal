<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Registro</h1>
            <p class="text-gray-500 text-sm mt-1">Administra y procesa las solicitudes de inscripción entrantes con eficiencia.</p>
        </div>
        <div class="flex items-center gap-3">
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-debts-modal')" class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
                <span class="material-symbols-outlined text-sm">payments</span>
                Facturar Semestre
            </button>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-fee-modal')" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
                <span class="material-symbols-outlined text-sm">add</span>
                Nuevo Arancel
            </button>
        </div>
    </div>

    <!-- Stats Ribbon -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Total Pendientes -->
        <div
            class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm font-semibold">Total Pendientes</p>
                <span class="p-2.5 bg-brand-50 text-brand-700 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="text-gray-900 text-3xl font-bold">{{ $stats['requests'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Last Request -->
        <div
            class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm font-semibold">Última Solicitud</p>
                <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-xl">schedule</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="text-gray-900 text-2xl font-bold">Hace 2 mins</p>
                <p class="text-gray-400 text-xs font-semibold">En vivo</p>
            </div>
        </div>

        <!-- Approved Today -->
        <div
            class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm font-semibold">Aprobadas Hoy</p>
                <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-xl">check_circle</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="text-gray-900 text-3xl font-bold">45</p>
            </div>
        </div>

        <!-- Tasa BCV (Admin Sync) -->
        <div x-data="{
            fetchingRate: false,
            async fetchBcvRate() {
                if(this.fetchingRate) return;
                this.fetchingRate = true;
                try {
                    const response = await fetch('{{ route('api.bcv.update') }}', {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    if(data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error al actualizar la tasa del BCV.');
                    }
                } catch(e) { 
                    console.error(e); 
                    alert('No se pudo conectar con el servidor.');
                }
                this.fetchingRate = false;
            }
        }" class="group flex flex-col gap-3 rounded-2xl p-6 bg-slate-50 border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-brand-700 text-xs font-bold uppercase tracking-widest">Tasa BCV Oficial</p>
                <button @click="fetchBcvRate()" :disabled="fetchingRate" class="text-brand-600 hover:text-brand-800 transition-colors bg-white rounded-lg p-1.5 shadow-sm border border-gray-100 disabled:opacity-50" title="Sincronizar BCV">
                    <span class="material-symbols-outlined text-sm block" :class="{'animate-spin': fetchingRate}">sync</span>
                </button>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-gray-900 text-3xl font-bold">
                    {{ number_format(\Illuminate\Support\Facades\DB::table('exchange_rates')->latest('fetched_at')->first()->rate ?? 61.20, 2) }} 
                    <span class="text-sm font-medium text-gray-500">Bs/$</span>
                </p>
                <p class="text-[10px] text-gray-400 font-medium">
                    Última actualización: {{ \Illuminate\Support\Facades\DB::table('exchange_rates')->latest('fetched_at')->first() ? \Carbon\Carbon::parse(\Illuminate\Support\Facades\DB::table('exchange_rates')->latest('fetched_at')->first()->fetched_at)->diffForHumans() : 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Dashboard Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Announcements Column -->
        <div class="lg:col-span-2 space-y-8">
            <div
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-brand-800">campaign</span>
                        <h3 class="text-lg font-bold text-gray-900">Anuncios & Noticias</h3>
                    </div>
                    <a href="{{ route('admin.posts.index') }}"
                        class="px-4 py-1.5 bg-brand-50 text-brand-800 text-xs font-semibold rounded-lg hover:bg-brand-100 transition-colors">
                        Ver todas
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($recentPosts as $post)
                        <div
                            class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors group">
                            <div class="flex items-center gap-5">
                                <div
                                    class="size-12 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                                    @if($post->image_url)
                                        <img src="{{ $post->image_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <span class="material-symbols-outlined text-xl">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $post->title }}</h5>
                                    <div
                                        class="flex items-center gap-3 mt-1.5 text-xs font-medium">
                                        <span class="text-brand-700 capitalize">{{ $post->category }}</span>
                                        <span class="size-1 bg-gray-300 rounded-full"></span>
                                        <span class="text-gray-500">
                                            Por {{ explode(' ', $post->author->name)[0] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <span
                                class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-semibold border border-gray-200 group-hover:bg-brand-50 group-hover:text-brand-800 group-hover:border-brand-200 transition-colors shadow-sm">
                                {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                            </span>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-20">campaign</span>
                            <p class="text-sm font-semibold text-gray-500">No hay publicaciones recientes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Recent Activity -->
        <div class="lg:col-span-4 space-y-6">
            <div
                class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h4 class="text-sm font-bold text-gray-900 mb-6 uppercase tracking-wider">
                    Actualizaciones Recientes</h4>

                <div class="space-y-6">
                    <div class="flex gap-4 relative">
                        <div class="w-px h-full bg-gray-100 absolute left-[15px] top-8"></div>
                        <div
                            class="size-8 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center shrink-0 z-10 shadow-sm border border-brand-100">
                            <span class="material-symbols-outlined text-sm font-bold">person_add</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Nuevo Estudiante</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Carlos Ruiz se unió a la plataforma.</p>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1 block">Hace
                                5 min</span>
                        </div>
                    </div>

                    <div class="flex gap-4 relative">
                        <div class="w-px h-full bg-gray-100 absolute left-[15px] top-8"></div>
                        <div
                            class="size-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 z-10 shadow-sm border border-emerald-100">
                            <span class="material-symbols-outlined text-sm font-bold">check_circle</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Solicitud Aprobada</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Inscripción aceptada para María García.</p>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1 block">Hace
                                2 horas</span>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div
                            class="size-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 z-10 shadow-sm border border-blue-100">
                            <span class="material-symbols-outlined text-sm font-bold">campaign</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Nueva Noticia</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">"Calendario de Exámenes" publicado.</p>
                            <span
                                class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1 block">Ayer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="space-y-8" x-data="{ 
            newPostModal: {{ $errors->has('title') || $errors->has('content') || $errors->has('event_date') ? 'true' : 'false' }}, 
            postCategory: '{{ old('category', 'noticia') }}', 
            postLoading: false 
        }">

        <div
            class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="size-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center shadow-sm border border-brand-100">
                    <span class="material-symbols-outlined font-bold text-xl">campaign</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Anuncios & Noticias</h3>
                    <p class="text-xs font-medium text-gray-500">Comunicación institucional global</p>
                </div>
            </div>
            <button @click="newPostModal = true"
                class="w-full md:w-auto bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-sm">add</span> Nueva Publicación
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($posts as $post)
                <div
                    class="bg-white rounded-2xl border border-gray-200 p-6 space-y-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <span
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold capitalize border
                                        {{ $post->category === 'evento' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-brand-50 text-brand-700 border-brand-200' }}">
                            {{ $post->category }}
                        </span>
                        <span class="text-xs font-medium text-gray-400 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-base font-bold text-gray-900 leading-tight">
                            {{ $post->title }}
                        </h4>
                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                            {{ $post->content }}
                        </p>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center font-bold text-gray-600 text-[10px] uppercase border border-gray-200">
                                {{ substr($post->author->name, 0, 2) }}
                            </div>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ explode(' ', $post->author->name)[0] }}</span>
                        </div>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                            onsubmit="return confirm('¿Eliminar esta publicación?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- New Post Modal -->
        <template x-teleport="body">
            <div x-show="newPostModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="newPostModal = false"
                    class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

                <div class="bg-white relative w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-xl font-bold text-gray-900">Nueva Publicación</h3>
                        <button @click="newPostModal = false"
                            class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                        class="p-6 space-y-5" @submit="postLoading = true">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-gray-700">Título</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all text-sm shadow-inner placeholder-gray-400">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label
                                    class="text-sm font-semibold text-gray-700">Categoría</label>
                                <select name="category" required x-model="postCategory"
                                    class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all text-sm shadow-inner">
                                    <option value="noticia">Noticia</option>
                                    <option value="evento">Evento</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-sm font-semibold text-gray-700">Imagen</label>
                                <input type="file" name="image" accept="image/*"
                                    class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label
                                class="text-sm font-semibold text-gray-700">Contenido</label>
                            <textarea name="content" rows="4" required
                                class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all text-sm shadow-inner resize-none placeholder-gray-400">{{ old('content') }}</textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" :disabled="postLoading"
                                class="w-full bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white py-3.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-3 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                <template x-if="postLoading"><span
                                        class="animate-spin material-symbols-outlined text-sm">refresh</span></template>
                                <span x-text="postLoading ? 'Procesando...' : 'Publicar Ahora'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <!-- Create Fee Modal -->
    <x-modal name="create-fee-modal" focusable>
        <form method="post" action="{{ route('admin.fees.store') }}" class="p-8">
            @csrf

            <div class="flex items-center gap-3 text-brand-800 mb-6 flex-col justify-center text-center">
                <span class="material-symbols-outlined text-4xl bg-brand-50 p-3 rounded-full">request_quote</span>
                <h2 class="text-xl font-bold text-gray-900 mt-2">
                    Crear Nuevo Arancel
                </h2>
                <p class="text-sm text-gray-500 max-w-sm">
                    Añade un nuevo concepto de pago para que los estudiantes puedan seleccionarlo.
                </p>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label for="name" class="text-sm font-semibold text-gray-700">Nombre del Concepto</label>
                    <input id="name" name="name" type="text" required
                        class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400"
                        placeholder="Ej: Constancia de Notas" />
                </div>

                <div class="space-y-1.5">
                    <label for="price_usd" class="text-sm font-semibold text-gray-700">Precio en Dólares (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">$</span>
                        <input id="price_usd" name="price_usd" type="number" step="0.01" min="0.01" required
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400"
                            placeholder="0.00" />
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold rounded-xl transition-all shadow-sm text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Guardar Arancel
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Assign Debts Modal -->
    <x-modal name="assign-debts-modal" focusable>
        <form method="post" action="{{ route('admin.debts.assign') }}" class="p-8">
            @csrf

            <div class="flex items-center gap-3 text-brand-800 mb-6 flex-col justify-center text-center">
                <span class="material-symbols-outlined text-5xl text-rose-500 bg-rose-50 p-4 rounded-full">receipt_long</span>
                <h2 class="text-xl font-bold text-gray-900 mt-2">
                    Facturar Semestre a Estudiantes
                </h2>
                <p class="text-sm text-gray-500 max-w-sm mt-1 leading-relaxed">
                    Esta acción asignará un saldo pendiente (deuda) automáticamente a <strong>todos los estudiantes activos</strong> en el sistema de manera instantánea.
                </p>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label for="fee_id" class="text-sm font-semibold text-gray-700 block mb-2">Selecciona el concepto a facturar:</label>
                    <select id="fee_id" name="fee_id" required
                        class="w-full bg-white text-gray-900 border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all font-semibold shadow-sm">
                        <option value="" disabled selected>Elige un arancel...</option>
                        @foreach(\Illuminate\Support\Facades\DB::table('fees')->get() as $fee)
                            <option value="{{ $fee->id }}">
                                {{ $fee->name }} — ${{ number_format($fee->price_usd, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 text-rose-600 bg-rose-50 p-3 rounded-lg border border-rose-100">
                    <span class="material-symbols-outlined text-lg">warning</span>
                    <p class="text-xs font-semibold">Aviso: Esta acción masiva operará sobre todos los estudiantes con estatus "Activo".</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white font-semibold rounded-xl transition-all shadow-sm text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">send</span>
                    Sí, Generar Facturas
                </button>
            </div>
        </form>
    </x-modal>
</x-dashboard-layout>