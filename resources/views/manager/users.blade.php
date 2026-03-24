<x-dashboard-layout>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Personal y Alumnos</h1>
            <p class="text-gray-500 text-sm mt-1">Administra los accesos y registros de estudiantes y docentes en el
                portal.</p>
        </div>
    </div>

    <!-- Stats Ribbon -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div
            class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm font-semibold">Total Gestionados</p>
                <div
                    class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-lg">group</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-bold text-gray-900">{{ $users->total() }}</h3>
                <p class="text-gray-400 text-sm font-medium">Personas</p>
            </div>
        </div>
        <div
            class="group flex flex-col gap-3 rounded-2xl p-6 bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm font-semibold">Estado del Sistema</p>
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-lg">how_to_reg</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <h3 class="text-xl font-bold text-emerald-600 uppercase tracking-wide">Operativo</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Creation Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm">person_add</span>
                    </div>
                    Registrar Nuevo
                </h3>

                <form action="{{ route('manager.users.store') }}" method="POST" class="space-y-4"
                    x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre Completo</label>
                        <input type="text" name="name" required placeholder="Nombre del usuario"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" required placeholder="correo@ejemplo.com"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rol</label>
                        <select name="role" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all">
                            <option value="student">Estudiante</option>
                            <option value="teacher">Profesor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contraseña Temporal</label>
                        <input type="password" name="password" required placeholder="Min. 8 caracteres"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400">
                    </div>
                    <button type="submit" :disabled="loading"
                        class="w-full py-3 mt-2 bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <template x-if="loading"><span
                                class="animate-spin material-symbols-outlined text-sm">refresh</span></template>
                        <span x-text="loading ? 'Creando...' : 'Crear Cuenta Activa'"></span>
                    </button>
                    <p class="text-[11px] text-gray-500 text-center font-medium px-2 mt-4 leading-relaxed">
                        Nota: El usuario estará activo de inmediato y podrá iniciar sesión.
                    </p>
                </form>
            </div>
        </div>

        <!-- User Directory -->
        <div class="lg:col-span-2" x-data="{ 
                editingUser: null, 
                loadingAction: null,
                users: {{ json_encode($users->items()) }},

                async updateUser(e) {
                    const formData = new FormData(e.target);
                    const id = this.editingUser.id;
                    this.loadingAction = 'update-user';
                    try {
                        const response = await fetch(`/manager/users/${id}`, {
                            method: 'POST',
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
                            this.$dispatch('notify', {message: data.message, type: 'success'});
                        } else {
                            this.$dispatch('notify', {message: data.message || 'Error al actualizar', type: 'error'});
                        }
                    } catch(e) { 
                        console.error(e); 
                        this.$dispatch('notify', {message: 'Ocurrió un error en la conexión.', type: 'error'});
                    }
                    this.loadingAction = null;
                },

                async toggleStatus(id) {
                    this.loadingAction = 'toggle-' + id;
                    try {
                        const response = await fetch(`/manager/users/${id}/toggle-status`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if(data.success) {
                            const index = this.users.findIndex(u => u.id === id);
                            if(index !== -1) {
                                this.users[index].status = data.user.status;
                                this.$dispatch('notify', {message: data.message, type: 'success'});
                            }
                        }
                    } catch(e) { console.error(e); }
                    this.loadingAction = null;
                }
            }">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Directorio de Personal</h3>
                    <form action="{{ route('manager.users.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre o email..."
                            class="w-full sm:w-64 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all placeholder-gray-400">
                        <button type="submit"
                            class="p-2.5 bg-brand-50 text-brand-700 rounded-xl hover:bg-brand-100 transition-all border border-brand-100">
                            <span class="material-symbols-outlined text-sm block">search</span>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Usuario</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                    Rol</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                    Estado</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="user in users" :key="user.id">
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-gray-500 text-xs uppercase border border-gray-200"
                                                x-text="user.name.substring(0,2)">
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900" x-text="user.name"></p>
                                                <p class="text-xs text-gray-500 mt-0.5" x-text="user.email"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span x-text="user.role === 'teacher' ? 'Docente' : 'Estudiante'"
                                            :class="user.role === 'teacher' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-blue-50 text-blue-700 border-blue-200'"
                                            class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg border">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            :class="user.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'"
                                            class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg border"
                                            x-text="user.status === 'active' ? 'Activo' : 'Suspendido'">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2"
                                            x-show="loadingAction !== 'toggle-' + user.id">
                                            <button @click="toggleStatus(user.id)"
                                                class="p-2 border rounded-lg transition-all shadow-sm"
                                                :class="user.status === 'active' ? 'text-amber-500 border-amber-100 hover:bg-amber-50' : 'text-emerald-500 border-emerald-100 hover:bg-emerald-50'"
                                                :title="user.status === 'active' ? 'Suspender' : 'Activar'">
                                                <span class="material-symbols-outlined text-sm block"
                                                    x-text="user.status === 'active' ? 'block' : 'undo'"></span>
                                            </button>
                                            <button @click="editingUser = Object.assign({}, user)"
                                                class="p-2 bg-white border border-gray-200 text-gray-500 rounded-lg hover:text-brand-700 hover:border-brand-200 hover:bg-brand-50 transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-sm block">edit</span>
                                            </button>
                                        </div>
                                        <div x-show="loadingAction === 'toggle-' + user.id"
                                            class="flex justify-end p-2">
                                            <span
                                                class="animate-spin material-symbols-outlined text-brand-800 text-sm">refresh</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="users.length === 0">
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <span
                                                class="material-symbols-outlined text-4xl mb-3 opacity-50">group_off</span>
                                            <p class="text-sm font-semibold text-gray-500">No se encontraron usuarios
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Edit Modal -->
                <template x-teleport="body">
                    <div x-show="editingUser" x-cloak
                        class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"
                            @click="editingUser = null"></div>

                        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl relative overflow-hidden"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">

                            <div
                                class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                <h4 class="text-xl font-bold text-gray-900">Editar Usuario</h4>
                                <button @click="editingUser = null"
                                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-sm block">close</span>
                                </button>
                            </div>

                            <form @submit.prevent="updateUser($event)" class="p-8 space-y-5">
                                @csrf
                                <input type="hidden" name="_method" value="PATCH">

                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">Nombre Completo</label>
                                    <input type="text" name="name" :value="editingUser?.name" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">Email</label>
                                    <input type="email" name="email" :value="editingUser?.email" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">Rol</label>
                                    <select name="role" x-model="editingUser?.role" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all">
                                        <option value="student">Estudiante</option>
                                        <option value="teacher">Profesor</option>
                                    </select>
                                </div>

                                <div class="space-y-1.5 pt-2">
                                    <label class="text-sm font-semibold text-gray-700">Nueva Contraseña <span
                                            class="text-xs text-gray-400 font-normal">(Opcional)</span></label>
                                    <input type="password" name="password"
                                        placeholder="Dejar en blanco para mantener actual"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 outline-none transition-all placeholder-gray-400">
                                </div>

                                <div class="pt-4">
                                    <button type="submit" :disabled="loadingAction === 'update-user'"
                                        class="w-full bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white py-3.5 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                                        <template x-if="loadingAction === 'update-user'">
                                            <span class="animate-spin material-symbols-outlined text-sm">refresh</span>
                                        </template>
                                        <span
                                            x-text="loadingAction === 'update-user' ? 'Guardando cambios...' : 'Guardar Cambios'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                <div class="p-6 bg-slate-50 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>