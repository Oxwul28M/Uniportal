<x-dashboard-layout>
    <x-slot name="header_title">Historial de Pagos</x-slot>
    <x-slot name="header_subtitle">Consulta el estado de tus transacciones</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600">receipt_long</span>
                Mis Transacciones
            </h3>
            <a href="{{ route('student.payments.create') }}"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-blue-600/20 hover:scale-105 transition-all">
                + REPORTAR NUEVO PAGO
            </a>
        </div>

        <div class="premium-card overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Referencia
                        </th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Monto (Bs)</th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Monto ($)</th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-slate-600">
                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4 font-black text-slate-800 text-sm">{{ $payment->reference }}</td>
                            <td class="px-6 py-4 text-center text-sm font-bold text-slate-600">
                                {{ number_format($payment->amount_bs, 2) }}</td>
                            <td class="px-6 py-4 text-center text-sm font-black text-blue-600">
                                ${{ number_format($payment->amount_usd, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="px-3 py-1 text-[9px] font-black uppercase rounded-lg
                                    {{ $payment->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($payment->status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600 animate-pulse') }}">
                                    {{ $payment->status === 'approved' ? 'Aprobado' : ($payment->status === 'rejected' ? 'Rechazado' : 'Pendiente') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-30">
                                    <span class="material-symbols-outlined text-5xl">inbox</span>
                                    <p class="text-sm font-bold uppercase tracking-widest">No tienes pagos registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>