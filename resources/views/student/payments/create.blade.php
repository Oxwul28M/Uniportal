<x-dashboard-layout>
    <x-slot name="header_title">Reportar Pago</x-slot>
    <x-slot name="header_subtitle">Ingresa los datos de tu transferencia bancaria</x-slot>

    <div class="max-w-2xl mx-auto" x-data="{ 
        amountBs: '', 
        rate: {{ $exchangeRate->rate ?? 0 }}, 
        feePriceUsd: 0,
        loading: false,
        get amountUsd() {
            if (!this.amountBs || !this.rate) return 0;
            return (parseFloat(this.amountBs) / this.rate).toFixed(2);
        },
        get isInsufficient() {
            if (!this.feePriceUsd || !this.amountUsd) return false;
            return parseFloat(this.amountUsd) < parseFloat(this.feePriceUsd);
        }
    }">
        <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-8">
            <form action="{{ route('student.payments.store') }}" method="POST" class="space-y-6" @submit="loading = true">
                @csrf

                @if(session('error_monto'))
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 animate-bounce">
                        <span class="material-symbols-outlined text-rose-600">error</span>
                        <p class="text-xs font-black text-rose-600 uppercase tracking-widest">{{ session('error_monto') }}
                        </p>
                    </div>
                @endif

                <!-- Fee Selection -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-700 px-1">Concepto de
                        Pago Pendiente</label>
                    <select name="fee_id" required
                        @change="feePriceUsd = parseFloat($event.target.options[$event.target.selectedIndex].dataset.price)"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all appearance-none cursor-pointer">
                        <option value="" disabled selected>Selecciona una deuda pendiente...</option>
                        @forelse($fees as $debt)
                            <option value="{{ $debt->debt_id }}" data-price="{{ $debt->price_usd }}">
                                {{ $debt->name }} — ${{ number_format($debt->price_usd, 2) }}
                            </option>
                        @empty
                            <option value="" disabled>No tienes deudas pendientes.</option>
                        @endforelse
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-700 px-1">Número de
                            Referencia</label>
                        <input type="text" name="reference" required placeholder="Ej: 12345678"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-700 px-1">Monto en
                            Bolívares (Bs)</label>
                        <input type="number" step="0.01" name="amount_bs" required x-model="amountBs" placeholder="0.00"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                    </div>
                </div>

                <!-- Auto-calculated USD (Read only for student) -->
                <div class="p-6 bg-slate-50 border border-gray-100 rounded-xl space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-brand-700 mb-1">Cálculo Automático ($)</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900" x-text="amountUsd">0.00</span>
                                <span class="text-lg font-bold text-gray-500">$</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-500 mb-1">Tasa BCV Aplicada</p>
                            <p class="text-sm font-bold text-gray-900">{{ number_format($exchangeRate->rate ?? 0, 2) }}
                                Bs/$</p>
                        </div>
                    </div>

                    <div x-show="isInsufficient" x-cloak class="flex items-center gap-2 text-rose-600 animate-pulse bg-rose-50 p-3 rounded-lg border border-rose-100">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <p class="text-xs font-bold uppercase">MONTO INSUFICIENTE PARA ESTE ARANCEL</p>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" :disabled="isInsufficient || !amountBs || feePriceUsd === 0 || loading"
                        :class="(isInsufficient || !amountBs || feePriceUsd === 0 || loading) ? 'opacity-50 cursor-not-allowed' : 'hover:from-brand-900 hover:to-blue-700 shadow-md hover:-translate-y-0.5'"
                        class="w-full bg-gradient-to-r from-brand-800 to-blue-600 text-white py-3.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">send</span>
                        <span x-text="loading ? 'Enviando...' : 'Enviar Reporte de Pago'"></span>
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="block text-center mt-4 text-xs font-semibold text-gray-500 hover:text-gray-800 transition-colors">Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>