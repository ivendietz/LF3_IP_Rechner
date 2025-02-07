<?php
 
namespace App\Livewire;
 
use Livewire\Component;
 
class Counter extends Component
{
    public $count = 1;
    public $test = 0;
 
    public function increment()
    {
        $this->count++;
    }

    public function refreshSubscribers()
    {
        $this->test++;
    }
 
    public function decrement()
    {
        if($this->count > 0) 
        $this->count--;
    }

    public function mount()
    {
        $this->count = 10;
    }
 
    public function render()
    {
        return view('livewire.counter');
    }
}