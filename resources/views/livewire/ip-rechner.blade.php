<section>
    <div class="mb-3">
        <x-input-label for="ip" :value="__('IP-Adresse')" />
        <x-text-input wire:model.live="ip" id="ip" name="ip" type="text" class="mt-1 block w-full" required
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
        <x-primary-button wire:click='calculate'>{{ __('Berechnen') }}</x-primary-button>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-row-output label="IP" value="{{ $ip }}" />
            <x-row-output label="IP in Binary" value="{{ $ipBinary }}" />
        </div>
        <div>
            <x-row-output label="Subnetzmaske" value="{{ $subnet }}" />
            <x-row-output label="Subnetzmaske in Binary" value="{{ $subnetBinary }}" />
        </div>
    </div>
    @if (isset($this->netz))
        <header class="mt-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Ergebnisse') }}
            </h2>
        </header>
        <hr class="mb-3">
        <div>
            <x-row-output label="Netzadresse" value="{{ $netz }}" />
            <x-row-output label="Netzadresse in Binary" value="{{ $netzBinary }}" />
            <x-row-output label="Broadcast-Adresse" value="{{ $broadcast }}" />
            <x-row-output label="Broadcast-Adresse in Binary" value="{{ $broadcastBinary }}" />
            <x-row-output label="Erster Host" value="{{ $ersterHost }}" />
            <x-row-output label="Letzter Host" value="{{ $letzterHost }}" />
            <x-row-output label="Anzahl Hosts" value="{{ $anzahlHosts }}" />
        </div>
    @endif
</section>
