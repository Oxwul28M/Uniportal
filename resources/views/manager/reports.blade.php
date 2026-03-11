<x-dashboard-layout>
    <x-slot name="header_title">Análisis de Recaudación</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-slate-800">Histórico Mensual (USD)</h3>
                    <select
                        class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold outline-none">
                        <option>Año 2026</option>
                    </select>
                </div>

                <div class="space-y-6">
                    @foreach($monthlyEarnings as $report)
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                    {{ \Carbon\Carbon::create()->month($report->month)->translatedFormat('F') }}
                                </span>
                                <span
                                    class="text-sm font-black text-slate-900">${{ number_format($report->total, 2) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                @php $percent = min(100, ($report->total / 5000) * 100); @endphp
                                <div class="bg-blue-600 h-full rounded-full transition-all duration-1000"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="premium-card p-8 bg-[#1e293b] text-white">
                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-6">Resumen Ejecutivo</p>
                <div class="space-y-8">
                    <div>
                        <p class="text-xs font-bold text-blue-400 mb-1">Recaudación Total</p>
                        <h4 class="text-3xl font-black tracking-tighter">
                            ${{ number_format($monthlyEarnings->sum('total'), 2) }}</h4>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-blue-400 mb-1">Meta Anual</p>
                        <h4 class="text-3xl font-black tracking-tighter text-white/20">$60,000</h4>
                    </div>
                </div>
                <a href="{{ route('manager.reports.export') }}"
                    class="w-full mt-10 bg-blue-600 py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all text-center block">
                    EXPORTAR TODO A EXCEL
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>