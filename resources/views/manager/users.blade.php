<x-dashboard-layout>
    <x-slot name="header_title">Gestión de Personal y Alumnos</x-slot>

    <!-- ── Stats Ribbon for Manager ── -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="premium-card p-6 bg-white border-b-4 border-b-indigo-500">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Gestionados</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ $users->total() }} Personas</h3>
                </div>
            </div>
        </div>
        <div class="premium-card p-6 bg-white border-b-4 border-b-emerald-500">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado del Sistema</p>
                    <h3 class="text-2xl font-black text-emerald-600 uppercase tracking-tighter">Operativo</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- ── User Creation Form (Left/Top) ── -->
        <div class="lg:col-span-1">
            <div class="premium-card p-6 sticky top-24">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600 text-xl">person_add</span>
                    Registrar Nuevo
                </h3>

                <form action="{{ route('manager.users.store') }}" method="POST" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nombre Completo</label>
                        <input type="text" name="name" required placeholder="Nombre del usuario"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email</label>
                        <input type="email" name="email" required placeholder="correo@ejemplo.com"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rol</label>
                        <select name="role" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                            <option value="student">Estudiante</option>
                            <option value="teacher">Profesor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Contraseña Temporal</label>
                        <input type="password" name="password" required placeholder="Min. 8 caracteres"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    </div>
                    <button type="submit" :disabled="loading" class="w-full py-3.5 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-900/10 flex items-center justify-center gap-3">
                        <template x-if="loading"><div class="spinner"></div></template>
                        <span x-text="loading ? 'CREANDO...' : 'Crear Cuenta Activa'"></span>
                    </button>
                    <p class="text-[9px] text-slate-400 text-center font-bold px-4">
                        Nota: El usuario estará activo de inmediato y podrá iniciar sesión con estas credenciales.
                    </p>
                </form>
            </div>
        </div>

        <!-- ── User Directory (Right) ── -->
        <div class="lg:col-span-2" 
            x-data="{ 
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
                        }
                    } catch(e) { console.error(e); }
                    this.loadingAction = null;
                }
            }">
            <div class="premium-card overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Directorio de Personal</h3>
                    <form action="{{ route('manager.users.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                            class="bg-white border border-slate-100 rounded-xl px-4 py-2 text-[10px] outline-none focus:ring-2 focus:ring-blue-400">
                        <button type="submit" class="p-2 bg-blue-600 text-white rounded-xl hover:bg-black transition-all">
                            <span class="material-symbols-outlined text-sm">search</span>
                        </button>
                    </form>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Usuario</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Rol</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="user in users" :key="user.id">
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div x-text="user.name.substring(0,2)"
                                            class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center font-black text-indigo-400 text-[10px] uppercase">
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-800" x-text="user.name"></p>
                                            <p class="text-[9px] text-slate-400 font-bold" x-text="user.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span x-text="user.role"
                                        :class="user.role === 'teacher' ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600'"
                                        class="px-2 py-0.5 text-[8px] font-black uppercase rounded">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="editingUser = Object.assign({}, user)" class="p-2 text-slate-400 hover:text-blue-600 transition-all">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template x-if="users.length === 0">
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                                    No hay usuarios registrados bajo tu gestión
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Edit Modal -->
                <template x-teleport="body">
                    <div x-show="editingUser" x-cloak
                        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                        <div class="bg-white rounded-[2.5rem] w-full max-w-md p-10 shadow-2xl relative"
                            @click.away="editingUser = null">
                            <button @click="editingUser = null"
                                class="absolute top-8 right-8 text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                            <h4 class="text-2xl font-black text-slate-800 mb-8">Editar Personal</h4>
                            <form @submit.prevent="updateUser($event)" class="space-y-6">
                                @csrf
                                <input type="hidden" name="_method" value="PATCH">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nombre</label>
                                    <input type="text" name="name" :value="editingUser?.name" required
                                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Email</label>
                                    <input type="email" name="email" :value="editingUser?.email" required
                                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Rol</label>
                                    <select name="role" x-model="editingUser?.role" required
                                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-400 outline-none transition-all appearance-none">
                                        <option value="student">Estudiante</option>
                                        <option value="teacher">Profesor</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nueva Contraseña (Opcional)</label>
                                    <input type="password" name="password" placeholder="Mín. 8 caracteres"
                                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-400 outline-none transition-all">
                                </div>
                                <button type="submit" :disabled="loadingAction === 'update-user'"
                                    class="w-full bg-blue-600 text-white py-4 rounded-2xl text-sm font-black shadow-xl shadow-blue-600/20 hover:bg-black transition-all uppercase tracking-widest disabled:opacity-50 flex items-center justify-center gap-3">
                                    <template x-if="loadingAction === 'update-user'">
                                        <div class="spinner"></div>
                                    </template>
                                    <span x-text="loadingAction === 'update-user' ? 'ACTUALIZANDO...' : 'ACTUALIZAR DATOS'"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
                <div class="p-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
