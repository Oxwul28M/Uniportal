<x-dashboard-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Validación de Pagos</h1>
            <p class="text-gray-500 text-sm mt-1">Revisa y aprueba los pagos reportados por los estudiantes.</p>
        </div>
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-fee-modal')"
            class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            Asignar Deuda
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
                        Monto Bs / REF</th>
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
                                <span
                                    class="bg-amber-100 text-amber-700 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-widest border border-amber-200">Deuda
                                    por Pagar</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <code
                                class="text-xs text-gray-500 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-lg">{{ $payment->reference }}</code>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-sm font-bold text-gray-900">{{ number_format($payment->amount_bs, 2) }} Bs</p>
                            <p class="text-xs text-emerald-600 font-bold mt-0.5">
                                REF {{ number_format($payment->amount_usd, 2) }}</p>
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

    <!-- Assign Debts Modal -->
    <x-modal name="create-fee-modal" focusable>
        <form method="post" action="{{ route('manager.debts.assign') }}" class="p-8" x-data="{ targetType: 'all' }">
            @csrf

            <div class="flex items-center gap-3 text-brand-800 mb-6 flex-col justify-center text-center">
                <span
                    class="material-symbols-outlined text-5xl text-rose-500 bg-rose-50 p-4 rounded-full">receipt_long</span>
                <h2 class="text-xl font-bold text-gray-900 mt-2">
                    Asignar Nueva Deuda
                </h2>
                <p class="text-sm text-gray-500 max-w-sm mt-1 leading-relaxed">
                    Añade un nuevo concepto de pago y factúralo a estudiantes específicos o a toda la matrícula de
                    inmediato.
                </p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Título / Nombre del Concepto</label>
                        <input type="text" name="title" required placeholder="Ej: Pago de Mensualidad"
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all font-semibold shadow-sm">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700">Precio en Referencia (REF)</label>
                        <input type="number" step="0.01" min="0.01" name="price_usd" required placeholder="0.00"
                            class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all font-semibold shadow-sm">
                    </div>
                </div>

                <div class="space-y-1.5 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="text-sm font-semibold text-gray-700 block mb-2">¿A quién se lo facturamos?</label>
                    <select name="target_type" required x-model="targetType"
                        class="w-full bg-white text-gray-900 border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all font-semibold shadow-sm cursor-pointer">
                        <option value="all">A todos los estudiantes activos</option>
                        <option value="course">A una clase / curso en específico</option>
                        <option value="student">A un estudiante en específico</option>
                    </select>
                </div>

                <div class="space-y-1.5 bg-gray-50 p-4 rounded-xl border border-gray-200"
                    x-show="targetType === 'course'" x-cloak>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Selecciona la Clase / Curso</label>
                    <select name="course_id" :required="targetType === 'course'"
                        class="w-full bg-white text-gray-900 border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all font-semibold shadow-sm cursor-pointer">
                        <option value="" disabled selected>Elige un curso...</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5 bg-gray-50 p-4 rounded-xl border border-gray-200"
                    x-show="targetType === 'student'" x-cloak>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Selecciona el Estudiante</label>
                    <select name="student_id" :required="targetType === 'student'"
                        class="w-full bg-white text-gray-900 border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all font-semibold shadow-sm cursor-pointer">
                        <option value="" disabled selected>Elige un estudiante...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 text-brand-600 bg-brand-50 p-3 rounded-lg border border-brand-100">
                    <span class="material-symbols-outlined text-lg">info</span>
                    <p class="text-[11px] uppercase font-bold tracking-widest">Las cuotas aparecerán de forma inmediata
                        a los seleccionados.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold rounded-xl transition-all shadow-sm text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add_task</span>
                    Guardar y Asignar
                </button>
            </div>
        </form>
    </x-modal>
</x-dashboard-layout>