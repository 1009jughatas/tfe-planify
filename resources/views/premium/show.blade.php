<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Abonnement Premium') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card">
            <div class="card-header">
                <h3>{{ __('Passez à Premium') }}</h3>
            </div>
            <div class="card-body">
                <p>Accédez à des fonctionnalités exclusives en passant à un compte premium.</p>
                <form action="{{ route('premium.purchase') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        Passez à Premium pour 10 $
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>