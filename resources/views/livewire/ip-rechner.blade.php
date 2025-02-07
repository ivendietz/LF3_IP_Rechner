<section>
    <header class="mb-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <div>
        <x-input-label for="ip" :value="__('IP-Adresse')" />
        <x-text-input wire:model.live.change="ip" id="ip" name="ip" type="text" class="mt-1 block w-full" required
            autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('ip')" />
    </div>

    <div>
        <x-input-label for="subnet" :value="__('Subnetzmaske')" />
        <x-text-input wire:model.live="subnet" id="subnet" name="subnet" type="text" class="mt-1 block w-full"
            required autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('subnet')" />
    </div>

    <div class="flex items-center gap-4 mt-4 mb-4">
        <x-primary-button wire:click='berechne'>{{ __('Berechnen') }}</x-primary-button>

        <x-action-message class="me-3" on="berechneIP">
            {{ __('Saved.') }}
        </x-action-message>
    </div>

    <div wire:poll>
        IP: {{ $ip }} <br>
        Binary IP: {{ $ipBinary }} <br>
        <br>
        Subnetzmaske: {{ $subnet }} <br>
        Binary Subnetzmaske: {{ $subnetBinary }} <br>
    </div>


</section>
