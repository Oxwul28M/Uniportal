<x-dashboard-layout>
    <x-slot name="header_title">Tramites y Documentos</x-slot>
    <x-slot name="header_subtitle">Solicita solvencias, constancias y otros documentos académicos</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Requestable Items -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3 px-2">
                <span class="material-symbols-outlined text-blue-600">note_add</span>
                Nueva Solicitud
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <form action="{{ route('student.documents.store') }}" method="POST" class="h-full">
                    @csrf
                    <input type="hidden" name="document_type" value="Constancia de Estudios">
                    <button type="submit" class="w-full premium-card p-6 flex flex-col md:flex-row items-center gap-5 text-center md:text-left hover:border-blue-400 group transition-all h-full">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">task</span>
                        </div>
                        <div>
                            <h5 class="text-sm font-black text-slate-800">Constancia de Estudios</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 md:mt-0">Entrega: 48 Horas</p>
                        </div>
                    </button>
                </form>

                <form action="{{ route('student.documents.store') }}" method="POST" class="h-full">
                    @csrf
                    <input type="hidden" name="document_type" value="Record de Notas">
                    <button type="submit" class="w-full premium-card p-6 flex flex-col md:flex-row items-center gap-5 text-center md:text-left hover:border-blue-400 group transition-all h-full">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                        </div>
                        <div>
                            <h5 class="text-sm font-black text-slate-800">Record de Notas</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 md:mt-0">Entrega: 72 Horas</p>
                        </div>
                    </button>
                </form>

                <form action="{{ route('student.documents.store') }}" method="POST" class="h-full">
                    @csrf
                    <input type="hidden" name="document_type" value="Solvencia Administrativa">
                    <button type="submit" class="w-full premium-card p-6 flex flex-col md:flex-row items-center gap-5 text-center md:text-left hover:border-blue-400 group transition-all h-full">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">verified_user</span>
                        </div>
                        <div>
                            <h5 class="text-sm font-black text-slate-800">Solvencia Administrativa</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 md:mt-0">Entrega: Inmediata</p>
                        </div>
                    </button>
                </form>

                <form action="{{ route('student.documents.store') }}" method="POST" class="h-full">
                    @csrf
                    <input type="hidden" name="document_type" value="Cambio de Carrera">
                    <button type="submit" class="w-full premium-card p-6 flex flex-col md:flex-row items-center gap-5 text-center md:text-left hover:border-blue-400 group transition-all h-full">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl">sync</span>
                        </div>
                        <div>
                            <h5 class="text-sm font-black text-slate-800">Cambio de Carrera</h5>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 md:mt-0">Requiere Entrevista</p>
                        </div>
                    </button>
                </form>
            </div>
        </div>

        <!-- Request Status -->
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3 px-2">
                <span class="material-symbols-outlined text-blue-600">history</span>
                Mis Solicitudes
            </h3>

            <div class="premium-card bg-white min-h-[300px] overflow-hidden">
                @if($requests->isEmpty())
                    <div class="flex flex-col items-center justify-center gap-4 h-[300px] opacity-20 p-6">
                        <span class="material-symbols-outlined text-6xl">assignment</span>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-center">No tienes trámites en curso</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 h-full max-h-[500px] overflow-y-auto">
                        @foreach($requests as $request)
                            <div class="p-5 hover:bg-gray-50 transition-colors flex items-center justify-between">
                                <div>
                                    <h5 class="text-sm font-bold text-gray-900">{{ $request->document_type }}</h5>
                                    <p class="text-[10px] text-gray-500 font-medium mt-1">
                                        Solicitado: {{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($request->status === 'pending')
                                        <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-200 uppercase tracking-wider">
                                            Pendiente
                                        </span>
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'cancel-document-{{ $request->id }}')" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-colors" title="Cancelar Solicitud">
                                            <span class="material-symbols-outlined text-sm">cancel</span>
                                        </button>
                                        
                                        <x-modal name="cancel-document-{{ $request->id }}" focusable>
                                            <form method="post" action="{{ route('student.documents.cancel', $request->id) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <div class="flex items-center gap-3 text-rose-600 mb-4 flex-col justify-center text-center">
                                                    <span class="material-symbols-outlined text-5xl bg-rose-50 p-4 rounded-full">cancel</span>
                                                    <h2 class="text-xl font-bold text-gray-900 mt-2">
                                                        Cancelar Solicitud
                                                    </h2>
                                                    <p class="text-sm text-gray-500 max-w-sm mt-1 leading-relaxed">
                                                        ¿Estás seguro de que deseas cancelar la solicitud de <strong>{{ $request->document_type }}</strong>? Esta acción no se puede deshacer.
                                                    </p>
                                                </div>

                                                <div class="mt-8 flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')"
                                                        class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                                                        Volver
                                                    </button>
                                                    <button type="submit"
                                                        class="px-5 py-2.5 bg-rose-600 text-white font-semibold rounded-xl transition-all shadow-sm text-sm hover:bg-rose-700 flex items-center gap-2">
                                                        <span class="material-symbols-outlined text-sm">delete</span>
                                                        Sí, cancelar
                                                    </button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @elseif($request->status === 'processing')
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 uppercase tracking-wider">
                                            En Proceso
                                        </span>
                                    @elseif($request->status === 'ready')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-200 uppercase tracking-wider">
                                            Lista
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-lg border border-rose-200 uppercase tracking-wider">
                                            Rechazada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout>