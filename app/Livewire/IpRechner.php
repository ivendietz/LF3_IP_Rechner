<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class IpRechner extends Component
{

    #[Validate(['required', 'min:7', 'max:15', 'ip'])]
    public $ip = '152.157.0.15';

    #[Validate(['required', 'min:7', 'max:15', 'regex:/^(?:255|254|252|248|240|224|192|128|0)\.0\.0\.0|255\.(?:255|254|252|248|240|224|192|128|0)\.0\.0|255\.255\.(?:255|254|252|248|240|224|192|128|0)\.0|255\.255\.255\.(?:255|254|252|248|240|224|192|128|0)$/'])]
    public $subnet = '255.255.255.0';

    public $ipBinary;
    public $subnetBinary;

    public $netz;
    public $netzBinary;
    public $broadcast;
    public $broadcastBinary;
    public $ersterHost;
    public $letzterHost;
    public $anzahlHosts;

    public function mount()
    {
        $this->updatedIp($this->ip);
        $this->updatedSubnet($this->subnet);
    }

    public function calculate()
    {
        $this->validate();

        $ipAsLong = ip2long($this->ip);
        $subnetAsLong = ip2long($this->subnet);

        $netzAsLong = $this->calculateNetworkAddress($ipAsLong, $subnetAsLong);
        $this->netz = long2ip($netzAsLong);
        $this->netzBinary = $this->ipToBinary($this->netz);

        $broadcastAsLong = $this->calculateBroadcastAddress($ipAsLong, $subnetAsLong);
        $this->broadcast = long2ip($broadcastAsLong);
        $this->broadcastBinary = $this->ipToBinary($this->broadcast);

        $this->ersterHost = long2ip($this->calculateFirstHost($netzAsLong));
        $this->letzterHost = long2ip($this->calculateLastHost($broadcastAsLong));
        $this->anzahlHosts = $this->calculateNumberOfHosts($subnetAsLong);
    }

    public function updatedIp($value)
    {
        $this->validate();
        $this->ipBinary = $this->ipToBinary($value);
    }

    public function updatedSubnet($value)
    {
        $this->validate();
        $this->subnetBinary = $this->ipToBinary($value);
    }

    private function calculateNetworkAddress($ip, $subnetMask)
    {
        return $ip & $subnetMask;
    }

    private function calculateBroadcastAddress($ip, $subnetMask)
    {
        return $ip | (~$subnetMask);
    }

    private function calculateFirstHost($netz)
    {
        return $netz + 1;
    }

    private function calculateLastHost($broadcast)
    {
        return $broadcast - 1;
    }

    private function calculateNumberOfHosts($subnetMask)
    {
        $binaryMask = decbin($subnetMask);
        $hostBits = substr_count($binaryMask, '0');
        return pow(2, $hostBits) - 2;
    }


    private function ipToBinary($ip)
    {
        return implode('.', array_map(function ($octet) {
            return str_pad(decbin($octet), 8, '0', STR_PAD_LEFT);
        }, explode('.', $ip)));
    }

    public function render()
    {
        return view('livewire.ip-rechner');
    }
}
