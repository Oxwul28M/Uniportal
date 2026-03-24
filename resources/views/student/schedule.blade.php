<x-dashboard-layout>
    <x-slot name="header_title">Mi Horario</x-slot>
    <x-slot name="header_subtitle">Horario semanal de clases vigentes</x-slot>

    <div class="space-y-8">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600">calendar_today</span>
                Clases de Hoy
            </h3>
            <span
                class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ now()->translatedFormat('l, d F') }}</span>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-3xl p-8 relative overflow-hidden">
            <!-- Background Decoration -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-brand-50 to-transparent rounded-full -translate-y-32 translate-x-32 opacity-70">
            </div>

            <div class="relative z-10 space-y-8">
                @foreach($schedule as $index => $class)
                    <div class="flex gap-6 group relative">
                        <!-- Timeline Line -->
                        @if (!$loop->last)
                            <div
                                class="absolute left-[31px] top-14 bottom-[-32px] w-px bg-gray-100 group-hover:bg-brand-200 transition-colors">
                            </div>
                        @endif

                        <!-- Time block -->
                        <div class="w-24 shrink-0 text-right pt-2">
                            <p class="text-sm font-bold text-gray-900">{{ explode(' - ', $class['time'])[0] }}</p>
                            <p class="text-[10px] font-semibold text-gray-400 mt-0.5">
                                {{ explode(' - ', $class['time'])[1] }}</p>
                        </div>

                        <!-- Connector Dot -->
                        <div class="relative shrink-0 pt-2.5">
                            <div
                                class="size-4 rounded-full bg-white border-4 border-brand-100 group-hover:border-brand-500 transition-colors z-10 relative shadow-sm">
                            </div>
                        </div>

                        <!-- Class Card -->
                        <div
                            class="flex-1 bg-gray-50 border border-gray-100 rounded-2xl p-5 group-hover:bg-white group-hover:border-brand-200 group-hover:shadow-md transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4
                                        class="text-lg font-bold text-gray-900 group-hover:text-brand-800 transition-colors">
                                        {{ $class['subject'] }}</h4>
                                    <div class="flex items-center gap-4 mt-2">
                                        <div class="flex items-center gap-1.5 text-gray-500">
                                            <span class="material-symbols-outlined text-sm">location_on</span>
                                            <span class="text-xs font-semibold">{{ $class['room'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-gray-500">
                                            <span class="material-symbols-outlined text-sm">person</span>
                                            <span class="text-xs font-semibold">{{ $class['teacher'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-blue-600">
                                            <span class="material-symbols-outlined text-sm">calendar_view_day</span>
                                            <span class="text-xs font-bold uppercase">{{ $class['day'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span
                                        class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-emerald-100">Presencial</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Full week placeholder -->
        <div
            class="premium-card p-12 bg-white border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center mt-8">
            <div
                class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mb-4 shadow-sm">
                <span class="material-symbols-outlined text-4xl">cloud_download</span>
            </div>
            <h4 class="text-sm font-black text-gray-900 uppercase tracking-[0.2em] mb-2">Descargar Horario Completo
            </h4>
            <p class="text-xs text-gray-500 max-w-xs leading-relaxed">Puedes descargar tu horario detallado en formato
                PDF para
                tenerlo disponible offline en tus dispositivos.</p>
            <a href="{{ route('student.schedule.export') }}"
                class="mt-6 bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 font-semibold px-6 py-3 rounded-xl text-sm transition-all shadow-md text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                Exportar PDF
            </a>
        </div>
    </div>
</x-dashboard-layout>