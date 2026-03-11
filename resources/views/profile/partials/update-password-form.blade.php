<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-brand-800">lock</span>
            Actualizar Contraseña
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5 max-w-xl">
        @csrf
        @method('put')

        <div class="space-y-1.5">
            <label for="update_password_current_password" class="text-sm font-semibold text-gray-700">Contraseña Actual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs text-red-600" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password" class="text-sm font-semibold text-gray-700">Nueva Contraseña</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs text-red-600" />
        </div>

        <div class="space-y-1.5">
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-gray-700">Confirmar Contraseña</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs text-red-600" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">key</span>
                Guardar Contraseña
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 px-3 py-1.5 rounded-lg"
                >
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Guardado
                </p>
            @endif
        </div>
    </form>
</section>
