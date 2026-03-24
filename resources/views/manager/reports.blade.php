<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Registro de Pagos Aprobados</h1>
            <p class="text-gray-500 text-sm mt-1">Historial completo de pagos validados en el sistema.</p>
        </div>
        <a href="{{ route(Auth::user()->role . '.reports.export') }}"
            class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">download</span>
            Exportar a Excel
        </a>
    </div>

    <!-- Main Content Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estudiante
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Concepto</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                            Referencia</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                            Monto (Bs / REF)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</span>
                                <span
                                    class="text-xs text-gray-500 block">{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $payment->student_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-[11px] text-brand-700 font-bold uppercase tracking-wider bg-brand-50 px-2.5 py-1.5 rounded-md border border-brand-100">
                                    {{ $payment->concept_name ?? 'Pago Genérico' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <code
                                    class="text-xs text-slate-600 bg-slate-100 border border-slate-200 px-2 py-1.5 rounded-lg tracking-widest font-bold">{{ $payment->reference }}</code>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-[13px] font-bold text-gray-900">{{ number_format($payment->amount_bs, 2) }}
                                    Bs</p>
                                <p
                                    class="text-[11px] text-emerald-700 font-bold mt-1 inline-flex items-center gap-1 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                    REF {{ number_format($payment->amount_usd, 2) }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center opacity-40">
                                <span class="material-symbols-outlined text-5xl mb-4 block">receipt_long</span>
                                <p class="text-sm font-bold uppercase tracking-widest">No hay pagos aprobados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>