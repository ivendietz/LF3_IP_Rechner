<?php

use Livewire\Attributes\Validate;
use Livewire\Form;

class IpRechnerForm extends Form
{
    #[Validate('required|string|min:7', onUpdate: true)]
    public string $email = '';

}