<x-dashboard-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Validación de Pagos</h1>
            <p class="text-gray-500 text-sm mt-1">Revisa y aprueba los pagos reportados por los estudiantes.</p>
        </div>
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-fee-modal')" class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            Nuevo Arancel
        </button>
    </div>

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Pagos por Procesar</h3>
        </div>

        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estudiante
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Concepto</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                        Referencia</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                        Monto Bs / USD</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $payment->student_name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <p class="text-xs text-brand-700 font-semibold uppercase tracking-wider">
                                    {{ $payment->fee_name }}
                                </p>
                                <span class="bg-amber-100 text-amber-700 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-widest border border-amber-200">Deuda por Pagar</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <code class="text-xs text-gray-500 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-lg">{{ $payment->reference }}</code>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-sm font-bold text-gray-900">{{ number_format($payment->amount_bs, 2) }} Bs</p>
                            <p class="text-xs text-emerald-600 font-bold mt-0.5">
                                +${{ number_format($payment->amount_usd, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('manager.payments.approve', $payment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-gradient-to-r from-brand-800 to-blue-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:from-brand-900 hover:to-blue-700 transition-all shadow-sm">
                                        Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('manager.payments.reject', $payment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-xl transition-all">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center opacity-30">
                            <span class="material-symbols-outlined text-5xl mb-4">fact_check</span>
                            <p class="text-xs font-black uppercase tracking-widest">No hay pagos pendientes en la cola</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-6 bg-slate-50 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Create Fee Modal -->
    <x-modal name="create-fee-modal" focusable>
        <form method="post" action="{{ route('manager.fees.store') }}" class="p-8">
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
</x-dashboard-layout>