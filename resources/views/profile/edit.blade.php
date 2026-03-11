<x-dashboard-layout>
    <!-- Page Header -->
    <div class="mb-8 relative">
        <h1 class="text-2xl font-bold text-gray-900">Mi Perfil</h1>
        <p class="text-gray-500 text-sm mt-1">Administra tu información personal y la seguridad de tu cuenta.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Settings Sidebar/Nav could go here, for now it's stacked -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
</x-dashboard-layout>
