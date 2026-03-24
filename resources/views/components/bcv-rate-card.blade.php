{{--
BCV Rate Card Component
Usage: <x-bcv-rate-card :rate="$latestRate" />
Props: $rate — objeto de exchange_rates (puede ser null)

Flujo:
1. "Sincronizar" → consulta API pública, muestra el valor SIN guardar
2. "Guardar Tasa" → guarda el valor mostrado en la BD (bcv.update)
--}}
@props(['rate' => null])

<div x-data="{
        loading:  false,
        saving:   false,
        fetched:  false,
        newRate:  null,

        async syncFromApi() {
            if (this.loading) return;
            this.loading = true;
            this.fetched = false;
            this.newRate = null;
            try {
                const res  = await fetch('{{ route('api.bcv.update') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                if (data.success) {
                    this.newRate  = parseFloat(data.new_rate).toFixed(2);
                    this.fetched  = true;

                    if (data.cached) {
                        this.$dispatch('notify', {
                            message: 'Ya existe un registro de hoy: Bs. ' + this.newRate,
                            type: 'info'
                        });
                    } else {
                        this.$dispatch('notify', {
                            message: '✓ Tasa obtenida: Bs. ' + this.newRate + ' — Haz clic en Guardar para confirmar.',
                            type: 'info'
                        });
                    }
                } else {
                    this.$dispatch('notify', {
                        message: data.message || 'No se pudo obtener la tasa.',
                        type: 'error'
                    });
                }
            } catch (e) {
                this.$dispatch('notify', { message: 'Error de conexión con la API BCV.', type: 'error' });
            }
            this.loading = false;
        },

        async saveRate() {
            if (this.saving || !this.newRate) return;
            this.saving = true;
            try {
                const res = await fetch('{{ route('bcv.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ rate: parseFloat(this.newRate) }),
                });
                const data = await res.json();
                if (data.success) {
                    this.$dispatch('notify', {
                        message: 'Tasa guardada en la base de datos correctamente.',
                        type: 'success'
                    });
                    this.fetched = false;
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    this.$dispatch('notify', {
                        message: data.message || 'Error al guardar.',
                        type: 'error'
                    });
                }
            } catch (e) {
                this.$dispatch('notify', { message: 'Error de conexión al guardar.', type: 'error' });
            }
            this.saving = false;
        }
    }"
    class="group flex flex-col gap-4 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <span class="p-2.5 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-xl">currency_exchange</span>
            </span>
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-widest">Tasa BCV Oficial</p>
                <p class="text-[10px] text-gray-400 font-medium">
                    @if($rate)
                        Guardada {{ \Carbon\Carbon::parse($rate->fetched_at)->diffForHumans() }}
                    @else
                        Sin datos aún
                    @endif
                </p>
            </div>
        </div>

        {{-- Status badge --}}
        @if($rate)
            @php $isToday = \Carbon\Carbon::parse($rate->fetched_at)->isToday(); @endphp
            <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wide
                    {{ $isToday
            ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
            : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                {{ $isToday ? 'Al día' : 'Desactualizada' }}
            </span>
        @endif
    </div>

    {{-- Current saved rate --}}
    <div class="flex items-baseline gap-1.5">
        <p class="text-gray-900 text-3xl font-bold tracking-tighter">
            {{ number_format($rate->rate ?? 0, 2) }}
        </p>
        <span class="text-sm font-semibold text-gray-400">Bs / REF</span>
    </div>

    {{-- Fetched preview (appears after sync) --}}
    <div x-show="fetched && newRate" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        class="flex items-center justify-between bg-amber-50 border border-amber-100 rounded-xl px-4 py-3">
        <div>
            <p class="text-[10px] text-amber-600 font-bold uppercase tracking-widest mb-0.5">Nuevo valor obtenido</p>
            <p class="text-xl font-bold text-gray-900" x-text="newRate + ' Bs / REF'"></p>
        </div>
        <span class="material-symbols-outlined text-amber-500 text-2xl">arrow_downward</span>
    </div>

    {{-- Actions --}}
    <div class="pt-1 border-t border-gray-100 flex gap-2">
        {{-- Sync button --}}
        <button @click="syncFromApi()" :disabled="loading || saving"
            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100 text-xs font-semibold transition-all disabled:opacity-40">
            <span class="material-symbols-outlined text-sm" :class="{ 'animate-spin': loading }">sync</span>
            <span x-text="loading ? 'Consultando...' : 'Consultar API'"></span>
        </button>

        {{-- Save button — only shown after fetching --}}
        <button x-show="fetched && newRate" @click="saveRate()" :disabled="saving"
            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white text-xs font-bold transition-all disabled:opacity-40 shadow-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <span class="material-symbols-outlined text-sm" :class="{ 'animate-spin': saving }">
                <template x-if="saving">refresh</template>
                <template x-if="!saving">save</template>
            </span>
            <span x-text="saving ? 'Guardando...' : 'Guardar Tasa'"></span>
        </button>
    </div>
</div>