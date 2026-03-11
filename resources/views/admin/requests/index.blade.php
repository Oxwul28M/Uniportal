<x-dashboard-layout>
    <div x-data="{ 
            loadingAction: null,
            requests: {{ json_encode($requests->items()) }},
            
            async processRequest(id, action) {
                this.loadingAction = action + '-' + id;
                try {
                    const response = await fetch(`/admin/requests/${id}/${action}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if(data.success) {
                        this.requests = this.requests.filter(r => r.id !== id);
        loadingAction: null,
        requests: {{ json_encode($requests->items()) }},
        
        async processRequest(id, action) {
            this.loadingAction = action + '-' + id;
            try {
                const response = await fetch(`/admin/requests/${id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if(data.success) {
                    this.requests = this.requests.filter(r => r.id !== id);
                }
            } catch(e) { console.error(e); }
            this.loadingAction = null;
        },
        search: '',
        exportData() {
            let csv = 'Estudiante,Email,Estado,Fecha\n';
            document.querySelectorAll('tbody tr').forEach(tr => {
                const name = tr.querySelector('.student-name').innerText;
                const email = tr.querySelector('.student-email').innerText;
                const status = tr.querySelector('.student-status').innerText;
                const date = tr.querySelector('.student-date').innerText;
                csv += `${name},${email},${status},${date}\n`;
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('href', url);
            a.setAttribute('download', 'solicitudes_registro.csv');
            a.click();
        }
    }">
        <!-- Page Header -->
        <div class="mb-8 relative">
            <h1 class="text-2xl font-bold text-gray-900">Solicitudes de Registro</h1>
            <p class="text-gray-500 text-sm mt-1">Gestiona y procesa las aplicaciones de inscripción de nuevos estudiantes.</p>
        </div>

        <!-- Stats Ribbon -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-gray-500 text-sm font-semibold">Total Pendientes</p>
                    <span class="p-2.5 bg-brand-50 text-brand-700 rounded-xl group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl">pending_actions</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-gray-900 text-3xl font-bold">{{ $requests->total() }}</p>
                </div>
            </div>
            <div class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-gray-500 text-sm font-semibold">Última Solicitud</p>
                    <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl">schedule</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-gray-900 text-2xl font-bold">2 min</p>
                    <p class="text-gray-400 text-xs font-semibold">En vivo</p>
                </div>
            </div>
            <div class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-gray-500 text-sm font-semibold">Aprobados Hoy</p>
                    <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl">check_circle</span>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-gray-900 text-3xl font-bold">45</p>
                </div>
            </div>
        </div>

        <!-- Main Table Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-brand-800">list_alt</span> 
                    Lista de Espera
                </h2>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">search</span>
                        <input type="text" x-model="search" placeholder="Filtrar por nombre..." 
                               class="w-full pl-9 pr-4 py-2 bg-gray-50 text-gray-900 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all shadow-inner placeholder-gray-500">
                    </div>
                    <button @click="exportData()" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-brand-800 to-blue-600 text-white rounded-xl text-xs font-semibold shadow-md transition-all hover:-translate-y-0.5 whitespace-nowrap">
                        <span class="material-symbols-outlined text-sm">download</span> Exportar
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Solicitud</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="reg in requests.filter(r => search === '' || r.user.name.toLowerCase().includes(search.toLowerCase()))" :key="reg.id">
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="size-10 rounded-full bg-brand-50 flex-shrink-0 flex items-center justify-center border border-brand-100 text-brand-700 font-bold">
                                            <img class="rounded-full w-full h-full object-cover" :src="reg.user.photo ? '/storage/' + reg.user.photo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(reg.user.name) + '&background=1e3a8a&color=fff'" :alt="reg.user.name">
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 group-hover:text-brand-800 transition-colors student-name" x-text="reg.user.name"></p>
                                            <p class="text-xs text-gray-500 student-email" x-text="reg.user.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200 student-status">
                                        Pendiente
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-600 student-date" x-text="new Date(reg.created_at).toLocaleDateString()"></p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2" x-show="loadingAction !== 'approve-' + reg.id && loadingAction !== 'reject-' + reg.id">
                                        <button @click="processRequest(reg.id, 'approve')" 
                                                class="size-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 hover:border-emerald-600" title="Aprobar">
                                            <span class="material-symbols-outlined text-lg">check</span>
                                        </button>
                                        <button @click="processRequest(reg.id, 'reject')" 
                                                class="size-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100 hover:border-red-600" title="Rechazar">
                                            <span class="material-symbols-outlined text-lg">close</span>
                                        </button>
                                    </div>
                                    <div class="flex justify-end p-2" x-show="loadingAction === 'approve-' + reg.id || loadingAction === 'reject-' + reg.id">
                                        <span class="animate-spin material-symbols-outlined text-brand-800 text-sm">refresh</span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="requests.filter(r => search === '' || r.user.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 opacity-30">
                                        <span class="material-symbols-outlined text-4xl text-gray-600">inbox</span>
                                        <p class="text-sm font-medium text-gray-600">No hay solicitudes pendientes</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout>