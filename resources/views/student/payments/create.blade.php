<x-dashboard-layout>
    <x-slot name="header_title">Reportar Pago</x-slot>
    <x-slot name="header_subtitle">Ingresa los datos de tu transferencia bancaria</x-slot>

    <!-- Agregar estilos para validación de Tailwind -->
    <style>
        .show-errors input:invalid, 
        .show-errors select:invalid {
            border-color: #e11d48; /* bg-rose-600 */
            background-color: #fff1f2; /* bg-rose-50 */
        }
        .show-errors input:invalid ~ .error-msg, 
        .show-errors select:invalid ~ .error-msg {
            display: block;
        }
    </style>

    <div class="max-w-2xl mx-auto" x-data="{ 
        amountBs: '', 
        paymentDate: '',
        historicalRates: {{ json_encode($historicalRates) }},
        latestRate: {{ $latestRate }},
        feePriceUsd: 0,
        loading: false,

        get currentRate() {
            if (!this.paymentDate) return this.latestRate;
            
            // Check exact match first
            if (this.historicalRates[this.paymentDate]) {
                return this.historicalRates[this.paymentDate];
            }

            // Fallback to nearest previous date
            let selectedDate = new Date(this.paymentDate.replace(/-/g, '/')); // block local timezone shift
            selectedDate.setHours(0,0,0,0);
            
            let fallbackRate = this.latestRate;
            let diff = Infinity;
            
            for (const [dateString, rate] of Object.entries(this.historicalRates)) {
                let recordDate = new Date(dateString.replace(/-/g, '/'));
                recordDate.setHours(0,0,0,0);
                
                if (recordDate <= selectedDate) {
                    let currentDiff = selectedDate - recordDate;
                    if (currentDiff < diff) {
                        diff = currentDiff;
                        fallbackRate = rate;
                    }
                }
            }
            return fallbackRate;
        },

        get amountUsd() {
            if (!this.amountBs || !this.currentRate) return 0;
            return (parseFloat(this.amountBs) / parseFloat(this.currentRate)).toFixed(2);
        },

        get requiredBs() {
            if (!this.feePriceUsd || !this.currentRate) return 0;
            return (parseFloat(this.feePriceUsd) * parseFloat(this.currentRate)).toFixed(2);
        },

        get isInsufficient() {
            if (!this.feePriceUsd || !this.amountUsd) return false;
            return parseFloat(this.amountUsd) < parseFloat(this.feePriceUsd);
        }
    }">
        <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-8 relative">
            
            <form action="{{ route('student.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate
                @submit.prevent="if($el.checkValidity()) { loading = true; $el.submit(); } else { $el.classList.add('show-errors'); }">
                @csrf

                @if(session('error_monto'))
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 animate-bounce">
                        <span class="material-symbols-outlined text-rose-600">error</span>
                        <p class="text-xs font-black text-rose-600 uppercase tracking-widest">{{ session('error_monto') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3">
                        <span class="material-symbols-outlined text-rose-600">error</span>
                        <p class="text-xs font-black text-rose-600 uppercase tracking-widest">{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex flex-col gap-2">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-rose-600">error</span>
                                <p class="text-xs font-black text-rose-600 tracking-widest">{{ $error }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Fee Selection -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-700 px-1">Concepto de Pago Pendiente <span class="text-rose-500">*</span></label>
                    <select name="fee_id" required
                        @change="feePriceUsd = parseFloat($event.target.options[$event.target.selectedIndex].dataset.price)"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all appearance-none cursor-pointer">
                        <option value="" disabled selected>Selecciona una deuda pendiente...</option>
                        @forelse($fees as $debt)
                            <option value="{{ $debt->debt_id }}" data-price="{{ $debt->price_usd }}">
                                {{ $debt->name }} — REF {{ number_format($debt->price_usd, 2) }}
                            </option>
                        @empty
                            <option value="" disabled>No tienes deudas pendientes.</option>
                        @endforelse
                    </select>
                    <p class="error-msg text-rose-600 text-[10px] font-bold hidden px-1 uppercase tracking-wider">Selecciona un concepto obligatorio.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-700 px-1">Fecha del Pago <span class="text-rose-500">*</span></label>
                        <input type="date" name="payment_date" required min="{{ date('Y-m-d', strtotime('-1 month')) }}" max="{{ date('Y-m-d') }}"
                            x-model="paymentDate"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all">
                        <p class="error-msg text-rose-600 text-[10px] font-bold hidden px-1 uppercase tracking-wider">La fecha es obligatoria.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-700 px-1">Número de Referencia (8 dígitos) <span class="text-rose-500">*</span></label>
                        <input type="text" name="reference" required pattern="[0-9]{8}" maxlength="8" minlength="8" placeholder="Ej: 12345678"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                        <p class="error-msg text-rose-600 text-[10px] font-bold hidden px-1 uppercase tracking-wider">Debe contener 8 números obligatorios.</p>
                    </div>
                </div>

                <!-- Tasa Display Info Box -->
                <div class="p-6 bg-brand-50 border border-brand-100 rounded-xl space-y-4" x-show="feePriceUsd > 0 && paymentDate">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-brand-700 mb-1">Monto de Deuda a Pagar (Bs)</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900" x-text="requiredBs">0.00</span>
                                <span class="text-lg font-bold text-gray-500">Bs</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-500 mb-1">Tasa BCV de la Fecha</p>
                            <p class="text-sm font-bold text-gray-900"><span x-text="parseFloat(currentRate).toFixed(2)"></span> Bs/REF</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-700 px-1">Monto Pagado a Reportar (Bs) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="amount_bs" required x-model="amountBs" placeholder="0.00"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                    <p class="error-msg text-rose-600 text-[10px] font-bold hidden px-1 uppercase tracking-wider">Monto es obligatorio.</p>
                </div>

                <!-- Conversion Preview -->
                <div class="p-6 bg-slate-50 border border-gray-100 rounded-xl space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-700 mb-1">Equivalente Transmitido (REF)</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-slate-900" x-text="amountUsd">0.00</span>
                                <span class="text-lg font-bold text-gray-500">REF</span>
                            </div>
                        </div>
                    </div>

                    <div x-show="isInsufficient" x-cloak class="flex items-center gap-2 text-rose-600 animate-pulse bg-rose-50 p-3 rounded-lg border border-rose-100">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <p class="text-xs font-bold uppercase">MONTO INSUFICIENTE PARA ESTE ARANCEL</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-700 px-1">Comprobante de Pago (JPG o PNG) <span class="text-rose-500">*</span></label>
                    <input type="file" name="receipt" required accept=".jpg,.jpeg,.png"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    <p class="error-msg text-rose-600 text-[10px] font-bold hidden px-1 uppercase tracking-wider">Debes subir tu comprobante obligatorio.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-gray-700 px-1">Observaciones (Opcional)</label>
                    <textarea name="observations" rows="3" placeholder="Añade algún comentario extra sobre el pago si es necesario..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" :disabled="isInsufficient || !amountBs || feePriceUsd === 0 || loading || !paymentDate"
                        :class="(isInsufficient || !amountBs || feePriceUsd === 0 || loading || !paymentDate) ? 'opacity-50 cursor-not-allowed' : 'hover:from-brand-900 hover:to-blue-700 shadow-md hover:-translate-y-0.5'"
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