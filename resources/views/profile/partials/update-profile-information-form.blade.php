<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-brand-800">person</span>
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Actualiza la información de perfil y la dirección de correo electrónico de tu cuenta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5 max-w-xl">
        @csrf
        @method('patch')

        <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-gray-700">Nombre Completo</label>
            <input id="name" name="name" type="text" class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-xs text-red-600" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1.5">
            <label for="email" class="text-sm font-semibold text-gray-700">Correo Electrónico</label>
            <input id="email" name="email" type="email" class="w-full bg-gray-50 text-gray-900 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-800/20 focus:border-brand-800 transition-all shadow-inner" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-xs text-red-600" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <p class="text-xs text-amber-800 font-medium">
                        Tu dirección de correo electrónico no está verificada.
                        <button form="send-verification" class="underline text-amber-900 hover:text-amber-700 font-bold ml-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Haz clic aquí para reenviar el correo de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-emerald-600">
                            Se ha enviado un nuevo enlace de verificación a tu correo.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-gradient-to-r from-brand-800 to-blue-600 hover:from-brand-900 hover:to-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
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
