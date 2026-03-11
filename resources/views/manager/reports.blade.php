<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Análisis de Recaudación</h1>
            <p class="text-gray-500 text-sm mt-1">Monitorea los ingresos financieros mensuales del sistema de gestión.</p>
        </div>
        <a href="{{ route('manager.reports.export') }}"
            class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">download</span>
            Exportar Excel
        </a>
    </div>

    <!-- Executive Summary Ribbon -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="group flex flex-col gap-3 rounded-2xl p-6 bg-gradient-to-br from-brand-800 to-blue-900 border border-brand-700 shadow-sm transition-all hover:shadow-md relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <p class="text-brand-100 text-sm font-semibold">Recaudación Total</p>
                <div class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 relative z-10 mt-2">
                <h3 class="text-3xl font-bold text-white">${{ number_format($monthlyEarnings->sum('total'), 2) }}</h3>
                <p class="text-brand-200 text-xs font-medium">USD</p>
            </div>
        </div>
        
        <div class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm font-semibold">Meta Anual (Proyectada)</p>
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:scale-110 transition-transform border border-slate-100">
                    <span class="material-symbols-outlined text-lg">flag</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <h3 class="text-xl font-bold text-gray-400">$60,000.00</h3>
                <p class="text-gray-400 text-xs font-medium">USD</p>
            </div>
            
            <!-- Mini progress -->
            @php $annualPercent = min(100, ($monthlyEarnings->sum('total') / 60000) * 100); @endphp
            <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden mt-1 opacity-50">
                <div class="bg-gray-400 h-full rounded-full" style="width: {{ $annualPercent }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">bar_chart</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Histórico de Ingresos por Mes</h3>
                        <p class="text-xs text-gray-500 font-medium">Volumen de recaudación aprobada</p>
                    </div>
                </div>
                <select class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all cursor-pointer">
                    <option>Año 2026</option>
                    <option>{{ date('Y') }}</option>
                </select>
            </div>

            <div class="p-8">
                <div class="space-y-8">
                    @forelse($monthlyEarnings as $report)
                        <div class="space-y-3 group">
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-bold text-gray-700 capitalize flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 block"></span>
                                    {{ \Carbon\Carbon::create()->month($report->month)->translatedFormat('F') }}
                                </span>
                                <span class="text-base font-bold text-gray-900">${{ number_format($report->total, 2) }}</span>
                            </div>
                            <!-- Max monthly target assumed 5000 for visualization -->
                            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden relative shadow-inner">
                                @php $percent = min(100, ($report->total / 5000) * 100); @endphp
                                <div class="bg-gradient-to-r from-brand-600 to-blue-500 h-full rounded-full transition-all duration-1000 ease-out group-hover:from-brand-500 group-hover:to-blue-400 group-hover:shadow-[0_0_10px_rgba(59,130,246,0.5)]"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 font-semibold text-right">{{ number_format($percent, 1) }}% del objetivo óptimo ($5K)</p>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-400">
                            <span class="material-symbols-outlined text-5xl mb-4 opacity-30 block">monitoring</span>
                            <p class="text-sm font-semibold text-gray-600 mb-1">No hay datos financieros registrados</p>
                            <p class="text-xs text-gray-400">Aún no se han procesado pagos en este ciclo.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if(count($monthlyEarnings) > 0)
            <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500 font-medium">Los datos mostrados corresponden únicamente a pagos en estado <span class="font-bold text-emerald-600">Aprobado</span>.</p>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>