<section class="space-y-6">
    <header class="mb-4">
        <h2 class="text-lg font-bold text-red-600 flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            Eliminar Cuenta
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato que desees conservar.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm"
    >
        Eliminar Cuenta
    </button>

    <!-- x-modal component isn't native to this project, user might have removed it -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 text-red-600 mb-4">
                <span class="material-symbols-outlined text-3xl">warning</span>
                <h2 class="text-xl font-bold">
                    ¿Estás seguro de que quieres eliminar tu cuenta?
                </h2>
            </div>

            <p class="mt-2 text-sm text-gray-500 bg-gray-50 p-4 rounded-xl border border-gray-100">
                Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. Por favor ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.
            </p>

            <div class="mt-6 space-y-1.5">
                <label for="password" class="text-sm font-semibold text-gray-700">Contraseña</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-white text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all shadow-inner"
                    placeholder="Tu contraseña"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs text-red-600" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Cancelar
                </button>

                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors shadow-sm text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">delete_forever</span>
                    Eliminar Permanentemente
                </button>
            </div>
        </form>
    </x-modal>
</section>
