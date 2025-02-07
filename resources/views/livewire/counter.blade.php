<div>
    <h1>{{ $count }}</h1>
 
    <button wire:loading.attr="disabled"  wire:click="increment">+</button>
 
    <button wire:loading.attr="disabled" wire:click="decrement">-</button>

<div wire:loading> 
    Saving post...
</div>
</div>
