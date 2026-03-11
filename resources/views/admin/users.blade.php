<x-dashboard-layout>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
            <p class="text-gray-500 text-sm mt-1">Administra el acceso institucional y los roles de usuario con precisión.</p>
        </div>
        <div class="flex items-center gap-3" x-data="{ search: '{{ request('search') }}' }">
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative group">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-brand-800 transition-colors text-xl">search</span>
                <input type="text" name="search" x-model="search" placeholder="Buscar usuarios..."
                    class="bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all w-full md:w-64 shadow-inner placeholder-gray-500">
            </form>
            <button @click="$dispatch('open-new-user-modal')"
                class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all flex items-center gap-2 shadow-md hover:-translate-y-0.5 whitespace-nowrap">
                <span class="material-symbols-outlined text-lg">person_add</span>
                <span class="hidden sm:inline">Nuevo Usuario</span>
            </button>
        </div>
    </div>

    <div class="space-y-6" x-data="{ 
            editingUser: null, 
            newUserModal: false,
            loadingAction: null,
            users: {{ json_encode($users->items()) }},
            
            async suspendUser(id) {
                if(!confirm('Are you sure you want to suspend this user?')) return;
                this.loadingAction = 'suspend-' + id;
                try {
                    const response = await fetch(`/admin/users/${id}/suspend`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if(data.success) {
                        const index = this.users.findIndex(u => u.id === id);
                        if(index !== -1) this.users[index].status = 'suspended';
                    }
                } catch(e) { console.error(e); }
                this.loadingAction = null;
            },

            async deleteUser(id) {
                if(!confirm('Permanently delete this user? This cannot be undone.')) return;
                this.loadingAction = 'delete-' + id;
                try {
                    const response = await fetch(`/admin/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if(data.success) {
                        this.users = this.users.filter(u => u.id !== id);
                    }
                } catch(e) { console.error(e); }
                this.loadingAction = null;
            },

            async storeUser(e) {
                const formData = new FormData(e.target);
                this.loadingAction = 'store-user';
                try {
                    const response = await fetch('{{ route('admin.users.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if(data.success) {
                        // Optimistically add to top of list if on first page
                        if(this.users.length < 15) {
                            this.users.unshift(data.user);
                        }
                        this.newUserModal = false;
                        e.target.reset();
                    } else if(data.errors) {
                        this.$dispatch('notify', {message: Object.values(data.errors).flat().join(' | '), type: 'error'});
                    }
                } catch(e) { console.error(e); }
                this.loadingAction = null;
            },

            async updateUser(e) {
                const formData = new FormData(e.target);
                const id = this.editingUser.id;
                this.loadingAction = 'update-user';
                try {
                    const response = await fetch(`/admin/users/${id}`, {
                        method: 'POST', // Laravel spoofing is used in form but for fetch we can use POST + _method
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    if(data.success) {
                        const index = this.users.findIndex(u => u.id === id);
                        if(index !== -1) this.users[index] = data.user;
                        this.editingUser = null;
                    }
                } catch(e) { console.error(e); }
                this.loadingAction = null;
            }
        }" @open-new-user-modal.window="newUserModal = true">

        <!-- Main User Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div
                class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-brand-800">group</span>
                    Directorio Institucional
                </h2>
                <div class="flex items-center gap-2">
                    <button @click="$dispatch('notify', {message: 'Filtros avanzados en desarrollo.', type: 'info'})" class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold border border-gray-200 transition-all hover:bg-gray-100 shadow-sm">
                        <span class="material-symbols-outlined text-sm">filter_list</span> Filtrar
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Información Personal</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Rol Institucional</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Estado</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <template x-for="user in users" :key="user.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 font-bold overflow-hidden border border-brand-100">
                                            <template x-if="user.photo">
                                                <img :src="'/storage/' + user.photo" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!user.photo">
                                                <span x-text="user.name.substring(0,1)"></span>
                                            </template>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900"
                                                x-text="user.name"></p>
                                            <p class="text-xs text-gray-500" x-text="user.email">
                                            </p>
                                        </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 capitalize"
                                        x-text="user.role"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold capitalize border"
                                        :class="user.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100'"
                                        x-text="user.status === 'active' ? 'Activo' : 'Suspendido'"></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2"
                                        x-show="loadingAction !== 'suspend-' + user.id && loadingAction !== 'delete-' + user.id">
                                        <button @click="editingUser = user"
                                            class="p-2 text-gray-400 hover:text-brand-800 hover:bg-brand-50 rounded-lg transition-colors" title="Editar">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </button>
                                        <button @click="suspendUser(user.id)"
                                            class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Suspender">
                                            <span class="material-symbols-outlined text-lg">block</span>
                                        </button>
                                        <button @click="deleteUser(user.id)"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                    <div class="flex justify-end p-2"
                                        x-show="loadingAction === 'suspend-' + user.id || loadingAction === 'delete-' + user.id">
                                        <span
                                            class="animate-spin material-symbols-outlined text-brand-800 text-sm">refresh</span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        </div>

        <!-- New User Modal -->
        <template x-teleport="body">
            <div x-show="newUserModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="newUserModal = false" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
                <div class="bg-white relative w-full max-w-md rounded-3xl shadow-2xl p-8"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <button @click="newUserModal = false"
                        class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>

                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Registrar Nuevo Usuario</h3>
                        <p class="text-sm text-gray-500 mt-1">Acceso Institucional
                        </p>
                    </div>

                    <form @submit.prevent="storeUser($event)" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-gray-700">Nombre Completo</label>
                            <input type="text" name="name" required
                                class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner placeholder-gray-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email" required
                                class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner placeholder-gray-400">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label
                                    class="text-sm font-semibold text-gray-700">Rol</label>
                                <select name="role" required
                                    class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner capitalize">
                                    <option value="student">student</option>
                                    <option value="teacher">teacher</option>
                                    <option value="manager">manager</option>
                                    <option value="admin">admin</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-sm font-semibold text-gray-700">Contraseña</label>
                                <input type="password" name="password" required
                                    class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner">
                            </div>
                        </div>
                        <button type="submit" :disabled="loadingAction === 'store-user'"
                            class="w-full bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white py-3.5 rounded-xl text-sm font-semibold transition-all mt-4 flex items-center justify-center gap-3 shadow-md hover:-translate-y-0.5">
                            <template x-if="loadingAction === 'store-user'">
                                <span class="animate-spin material-symbols-outlined text-sm">refresh</span>
                            </template>
                            <span x-text="loadingAction === 'store-user' ? 'REGISTRANDO...' : 'CREAR USUARIO'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </template>

        <!-- Edit User Modal -->
        <template x-teleport="body">
            <div x-show="editingUser" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div @click="editingUser = null" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
                <div class="bg-white relative w-full max-w-md rounded-3xl shadow-2xl p-8"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <button @click="editingUser = null"
                        class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>

                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Editar Usuario</h3>
                        <p class="text-sm text-gray-500 mt-1">Actualizar Información
                        </p>
                    </div>

                    <form @submit.prevent="updateUser($event)" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-gray-700">Nombre Completo</label>
                            <input type="text" name="name" x-model="editingUser.name" required
                                class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner placeholder-gray-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email" x-model="editingUser.email" required
                                class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner placeholder-gray-400">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label
                                    class="text-sm font-semibold text-gray-700">Rol</label>
                                <select name="role" x-model="editingUser.role" required
                                    class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner capitalize">
                                    <option value="student">student</option>
                                    <option value="teacher">teacher</option>
                                    <option value="manager">manager</option>
                                    <option value="admin">admin</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-gray-700">Contraseña (opcional)</label>
                                <input type="password" name="password" placeholder="En blanco para mantener actual"
                                    class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner">
                            </div>
                        </div>
                        <button type="submit" :disabled="loadingAction === 'update-user'"
                            class="w-full bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white py-3.5 rounded-xl text-sm font-semibold transition-all mt-4 flex items-center justify-center gap-3 shadow-md hover:-translate-y-0.5">
                            <template x-if="loadingAction === 'update-user'">
                                <span class="animate-spin material-symbols-outlined text-sm">refresh</span>
                            </template>
                            <span x-text="loadingAction === 'update-user' ? 'ACTUALIZANDO...' : 'GUARDAR CAMBIOS'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-dashboard-layout>