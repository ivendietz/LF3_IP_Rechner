<?php

namespace App\Livewire;

use Livewire\Component;

class IpRechner extends Component
{

    public $ip = '152.157.0.15';
    public $subnet = '255.255.255.0';

    public $ipBinary;
    public $subnetBinary;

    public $netz;
    public $broadcast;
    public $ersterHost;
    public $letzterHost;

    public function mount() {
        $this->updatedIp($this->ip);
        $this->updatedSubnet($this->subnet);
    }

    public function berechne() {
        
        $this->netz = $this->ip;
    }

    public function updatedIp($value) {
        $this->ipBinary = $this->ipToBinary($value);
    }

    public function updatedSubnet($value) {
        $this->subnetBinary = $this->ipToBinary($value);
    }

    private function ipToBinary($ip) {
        return implode('.', array_map(function($octet) {
            return str_pad(decbin($octet), 8, '0', STR_PAD_LEFT);
        }, explode('.', $ip)));
    }

    public function render()
    {
        return view('livewire.ip-rechner');
    }
}
