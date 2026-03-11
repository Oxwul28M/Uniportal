<x-dashboard-layout>
    <!-- Manager Banner -->
    <div x-data="{
        fetchingRate: false,
        async fetchBcvRate() {
            if(this.fetchingRate) return;
            this.fetchingRate = true;
            try {
                const response = await fetch('{{ route('api.bcv.update') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if(data.success) {
                    window.location.reload();
                } else {
                    this.$dispatch('notify', {message: data.message || 'Error al actualizar la tasa del BCV.', type: 'error'});
                }
            } catch(e) { 
                console.error(e); 
                this.$dispatch('notify', {message: 'No se pudo conectar con la API de BDV.', type: 'error'});
            }
            this.fetchingRate = false;
        }
    }">
    <div class="bg-white rounded-2xl p-10 mb-8 relative overflow-hidden shadow-sm border border-gray-200">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex-1">
                <h1 class="text-3xl font-bold tracking-tight leading-tight mb-2 text-gray-900">
                    Resumen de Operaciones
                </h1>
                <p class="text-gray-500 text-sm max-w-xl leading-relaxed">
                    Monitoreo constante de recaudación y validación de pagos. La tasa BCV es gestionada de forma externa para garantizar precisión.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-debts-modal')"
                        class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">payments</span>
                        Facturar Semestre
                    </button>
                    <a href="{{ route('manager.payments.index') }}"
                        class="bg-slate-50 border border-gray-200 text-brand-700 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-100 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">checklist</span>
                        Validar Pagos
                    </a>
                </div>
            </div>

            <div class="flex flex-col gap-3 shrink-0">
                <div class="bg-slate-50 p-6 rounded-2xl border border-gray-200 text-center min-w-[220px]">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-2 mb-3">
                        <p class="text-xs text-brand-700 font-bold uppercase tracking-widest">Tasa BCV Oficial</p>
                        <button @click="fetchBcvRate()" :disabled="fetchingRate" class="text-brand-600 hover:text-brand-800 transition-colors p-1 bg-brand-50 rounded-lg disabled:opacity-50" title="Sincronizar BCV">
                            <span class="material-symbols-outlined text-sm block" :class="{'animate-spin': fetchingRate}">sync</span>
                        </button>
                    </div>
                    <p class="text-3xl font-bold tracking-tighter text-gray-900">
                        {{ number_format($latestRate->rate ?? 61.20, 2) }} <span class="text-sm font-medium text-gray-500">Bs/$</span>
                    </p>
                    <p class="text-[10px] text-gray-400 mt-2 font-medium">
                        Última actualización: {{ $latestRate ? \Carbon\Carbon::parse($latestRate->fetched_at)->diffForHumans() : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Pending Payments Section -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-lg font-bold flex items-center gap-3 text-gray-900">
                    <span class="material-symbols-outlined text-gray-500">pending_actions</span>
                    Pagos Pendientes
                </h3>
                <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-lg text-xs font-bold border border-amber-200">
                    {{ $pendingCount }} Pendientes
                </span>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-700">Últimas transacciones reportadas</p>
                </div>
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl mb-4">layers</span>
                    <p class="text-sm font-medium text-gray-500 mb-6">Gestiona la cola de validación desde el módulo dedicado.</p>
                    <a href="{{ route('manager.payments.index') }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
                        Ir a Validaciones
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Cash Flow Section -->
        <div class="lg:col-span-4 space-y-6">
            <h3 class="text-lg font-bold flex items-center gap-3 px-2 text-gray-900">
                <span class="material-symbols-outlined text-gray-500">analytics</span>
                Flujo de Caja
            </h3>

            <div class="bg-white p-6 rounded-2xl relative overflow-hidden border border-gray-200 shadow-sm">
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-gray-700 mb-6">Ingresos del Mes</p>

                    @php
                        $target = 10000;
                        $percent = min(100, round(($totalUsd / $target) * 100));
                    @endphp

                    <div class="space-y-4">
                        <div class="flex items-end justify-between">
                            <p class="text-sm font-medium text-gray-500">Recaudado</p>
                            <p class="text-2xl font-bold tracking-tighter text-gray-900">${{ number_format($totalUsd, 2) }}</p>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-brand-600 h-full rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs font-semibold text-gray-500">
                            <span>Meta: ${{ number_format($target) }}</span>
                            <span class="text-brand-700">{{ $percent }}%</span>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('manager.reports.index') }}"
                            class="w-full bg-slate-50 text-brand-700 hover:bg-slate-100 border border-gray-200 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                            Ver Reportes Detallados
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Assign Debts Modal -->
    <x-modal name="assign-debts-modal" focusable>
        <form method="post" action="{{ route('manager.debts.assign') }}" class="p-8">
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
                    <p class="text-xs font-semibold">Aviso: Esta acción es masiva y no se puede deshacer de forma sencilla.</p>
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