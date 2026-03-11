<x-dashboard-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Seguridad del Sistema</h1>
        <p class="text-gray-500">Supervisa el estado del sistema y la auditoría de eventos de seguridad.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- System Health -->
        <div class="lg:col-span-4 space-y-6">
            <div
                class="bg-white p-8 rounded-xl border border-gray-200  shadow-sm transition-all hover:shadow-md">
                <div class="w-12 h-12 bg-brand-50 text-brand-700 rounded-xl flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined font-bold">shield_with_heart</span>
                </div>

                <h4 class="text-xl font-bold text-gray-900 mb-2">Estado del Sistema</h4>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-6">Todos los módulos operan con normalidad</p>

                <div class="space-y-4">
                    <div
                        class="flex items-center justify-between py-2 border-b border-gray-50 /50">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Certificado SSL</span>
                        <span
                            class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-[10px] rounded-full font-bold">ACTIVO</span>
                    </div>
                    <div
                        class="flex items-center justify-between py-2 border-b border-gray-50 /50">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Firewall Cloud</span>
                        <span
                            class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-[10px] rounded-full font-bold">PROTEGIENDO</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Respaldo Diario</span>
                        <span
                            class="px-2.5 py-0.5 bg-brand-50 text-brand-700 text-[10px] rounded-full font-bold">COMPLETO</span>
                    </div>
                </div>
            </div>

            <!-- Security Actions -->
            <div
                class="bg-white p-6 rounded-xl border border-gray-200  shadow-sm">
                <h5 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Acciones Rápidas</h5>
                <div class="space-y-2">
                    <button @click="$dispatch('notify', {message: 'Iniciando rotación de llaves del sistema...', type: 'info'})"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-bold uppercase tracking-widest transition-colors">
                        <span class="material-symbols-outlined text-lg">key</span>
                        Rotar Llaves Secretas
                    </button>
                    <button @click="$dispatch('notify', {message: 'Caché del sistema limpiada satisfactoriamente.', type: 'success'})"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-bold uppercase tracking-widest transition-colors">
                        <span class="material-symbols-outlined text-lg">terminal</span>
                        Limpiar Caché del Sistema
                    </button>
                </div>
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="lg:col-span-8">
            <div
                class="bg-white rounded-xl border border-gray-200  shadow-sm overflow-hidden">
                <div
                    class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-brand-800 rounded-full"></div>
                        <h3 class="text-lg font-bold text-gray-900">Registro de Auditoría</h3>
                    </div>
                    <span class="material-symbols-outlined text-brand-800">history</span>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    <div class="p-6 flex gap-6 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group">
                        <div
                            class="size-10 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800/50">
                            <span class="material-symbols-outlined text-sm">person_add</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Nuevo usuario registrado</p>
                            <p class="text-xs text-gray-500 mt-1">La cuenta de 'Ing. Carlos Ruiz' fue habilitada manualmente.</p>
                            <div class="flex items-center gap-3 mt-3">
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Hoy, 14:20
                                    PM</span>
                                <span class="size-1 bg-gray-200 rounded-full"></span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">IP:
                                    192.168.1.45</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex gap-6 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group">
                        <div
                            class="size-10 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0 border border-primary/20">
                            <span class="material-symbols-outlined text-sm">login</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Inicio de sesión exitoso</p>
                            <p class="text-xs text-gray-500 mt-1">Acceso detectado desde un nuevo dispositivo Windows.</p>
                            <div class="flex items-center gap-3 mt-3">
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Hoy, 10:05
                                    AM</span>
                                <span class="size-1 bg-gray-200 rounded-full"></span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Chrome
                                    Desktop</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex gap-6 hover:bg-red-50/50 dark:hover:bg-red-900/10 transition-colors group">
                        <div
                            class="size-10 rounded-lg bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 flex items-center justify-center shrink-0 border border-red-200 dark:border-red-800/50">
                            <span class="material-symbols-outlined text-sm">warning</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-red-600 dark:text-red-400">Intento de acceso fallido</p>
                            <p class="text-xs text-gray-500 mt-1">Múltiples intentos denegados para 'manager@testing.com'.</p>
                            <div class="flex items-center gap-3 mt-3">
                                <span class="text-[9px] text-red-400 font-bold uppercase tracking-widest">Ayer,
                                    23:45 PM</span>
                                <span class="size-1 bg-red-200 rounded-full"></span>
                                <span class="text-[9px] text-red-400 font-bold uppercase tracking-widest">IP:
                                    45.12.90.11</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button @click="$dispatch('notify', {message: 'Generando Reporte de Auditoría... La descarga comenzará pronto.', type: 'info'})"
                    class="w-full py-4 bg-white text-[10px] font-bold uppercase tracking-widest text-gray-800 hover:text-brand-800 transition-all border-t border-gray-100 flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-sm">download</span> Descargar Reporte de Auditoría
                </button>
            </div>
        </div>
    </div>
</x-dashboard-layout>
