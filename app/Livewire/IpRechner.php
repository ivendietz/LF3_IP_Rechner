<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Component zur Berechnung von Netzwerkinformationen basierend auf einer gegebenen IP-Adresse und Subnetzmaske.
 */
class IpRechner extends Component
{
    /**
     * @var string Die IP-Adresse des Netzwerks. 
     * - Nutzer eingaben als Model im Template
     */
    #[Validate(["required", "min:7", "max:15", "ip"])]
    public string $ip = '152.157.0.15';

    /**
     * @var string Die Subnetzmaske des Netzwerks.
     * - Nutzer eingaben als Model im Template
     */
    #[Validate(['required', 'min:7', 'max:15', 'regex:/^(?:255|254|252|248|240|224|192|128|0)\.0\.0\.0|255\.(?:255|254|252|248|240|224|192|128|0)\.0\.0|255\.255\.(?:255|254|252|248|240|224|192|128|0)\.0|255\.255\.255\.(?:255|254|252|248|240|224|192|128|0)$/'])]
    public string $subnet = '255.255.255.0';

    /** 
     * @var string Binärdarstellung der IP-Adresse.
     * - Ausgaben Variable für das Template
     */
    public string $ipBinary;
    
    /** 
     * @var string Binärdarstellung der Subnetzmaske. 
     * - Ausgaben Variable
     */
    public $subnetBinary;
    
    /** 
     * @var string Netzwerkadresse in dezimaler Darstellung.
     * - Ausgaben Variable für das Template
     */
    public string $netz;
    
    /** 
     * @var string Netzwerkadresse in binärer Darstellung. 
     * - Ausgaben Variable für das Template
     */
    public string $netzBinary;
    
    /** 
     * @var string Broadcast-Adresse des Netzwerks. 
     * - Ausgaben Variable für das Template
     */
    public string $broadcast;
    
    /**
     *  @var string Broadcast-Adresse in binärer Darstellung. 
     * - Ausgaben Variable für das Template
     */
    public string $broadcastBinary;
    
    /** 
     * @var string Erste gültige Host-Adresse.
     * - Ausgaben Variable für das Template
     */
    public string $ersterHost;
    
    /** 
     * @var string Letzte gültige Host-Adresse.
     * - Ausgaben Variable für das Template
     */
    public string $letzterHost;
    
    /** 
     * @var int Anzahl der verfügbaren Hosts im Netzwerk. 
     * - Ausgaben Variable für das Template
     */
    public int $anzahlHosts;

    /**
     * Initialisiert die Komponente und berechnet die initialen Werte.
     */
    public function mount(): void
    {
        $this->updatedIp($this->ip);
        $this->updatedSubnet($this->subnet);
    }

    /**
     * Führt die Netzwerkanalyse auf den Button Click durch und setzt die entsprechenden Werte.
     */
    public function calculate(): void
    {
        # Validierung der in der Annutation festgelgten Kritärien
        $this->validate();

        # Umrechnen der ip Adressen Eingaben in Binary Longs
        $ipAsLong = ip2long($this->ip);
        $subnetAsLong = ip2long($this->subnet);

        # Errechen der Netzwerk Adresse sowie das ausgeben in Binary
        $netzAsLong = $this->calculateNetworkAddress($ipAsLong, $subnetAsLong);
        $this->netz = long2ip($netzAsLong);
        $this->netzBinary = $this->ipToBinary($this->netz);

        # Errechen der Brodcast Adresse wie die ausgaben in Binary
        $broadcastAsLong = $this->calculateBroadcastAddress($ipAsLong, $subnetAsLong);
        $this->broadcast = long2ip($broadcastAsLong);
        $this->broadcastBinary = $this->ipToBinary($this->broadcast);

        #Errechen des erstens und des letztens Host sowie die Anzahl an Hosts und das übergeben an die Template Variablen
        $this->ersterHost = long2ip($this->calculateFirstHost($netzAsLong));
        $this->letzterHost = long2ip($this->calculateLastHost($broadcastAsLong));
        $this->anzahlHosts = number_format($this->calculateNumberOfHosts($subnetAsLong), 0, ',', '.');
    }

    /**
     * Aktualisiert die Binärdarstellung der IP-Adresse. Beim Input in das Feld
     * 
     * @param string $value Die neue IP-Adresse.
     */
    public function updatedIp(string $value): void
    {
        # Validierung der in der Annutation festgelgten Kritärien
        $this->validate();
        $this->ipBinary = $this->ipToBinary($value);
    }

    /**
     * Aktualisiert die Binärdarstellung der Subnetzmaske. Beim Input in das Feld
     * 
     * @param string $value Die neue Subnetzmaske.
     */
    public function updatedSubnet(string $value): void
    {
        # Validierung der in der Annutation festgelgten Kritärien
        $this->validate();
        $this->subnetBinary = $this->ipToBinary($value);
    }

    /**
     * Berechnet die Netzwerkadresse.
     * 
     * @param int $ip Die IP-Adresse als Ganzzahl.
     * @param int $subnetMask Die Subnetzmaske als Ganzzahl.
     * @return int Die berechnete Netzwerkadresse als Ganzzahl.
     */
    private function calculateNetworkAddress(int $ip, int $subnetMask): int
    {
        return $ip & $subnetMask;
    }

    /**
     * Berechnet die Broadcast-Adresse.
     * 
     * @param int $ip Die IP-Adresse als Ganzzahl.
     * @param int $subnetMask Die Subnetzmaske als Ganzzahl.
     * @return int Die berechnete Broadcast-Adresse als Ganzzahl.
     */
    private function calculateBroadcastAddress(int $ip, int $subnetMask): int
    {
        return $ip | (~$subnetMask);
    }

    /**
     * Berechnet die erste Host-Adresse im Netzwerk.
     * 
     * @param int $netz Die Netzwerkadresse als Ganzzahl.
     * @return int Die erste Host-Adresse als Ganzzahl.
     */
    private function calculateFirstHost(int $netz): int
    {
        return $netz + 1;
    }

    /**
     * Berechnet die letzte Host-Adresse im Netzwerk.
     * 
     * @param int $broadcast Die Broadcast-Adresse als Ganzzahl.
     * @return int Die letzte Host-Adresse als Ganzzahl.
     */
    private function calculateLastHost(int $broadcast): int
    {
        return $broadcast - 1;
    }

    /**
     * Berechnet die Anzahl der möglichen Hosts im Netzwerk. Anhande der Anzahl der '0' in der Subnetzmaske
     * 
     * @param int $subnetMask Die Subnetzmaske als Ganzzahl.
     * @return int Die Anzahl der möglichen Hosts.
     */
    private function calculateNumberOfHosts(int $subnetMask): int
    {
        $binaryMask = decbin($subnetMask);
        $hostBits = substr_count($binaryMask, '0');
        return pow(2, $hostBits) - 2;
    }

    /**
     * Konvertiert eine IP-Adresse in eine binäre Darstellung.
     * 
     * @param string $ip Die IP-Adresse.
     * @return string Die binäre Darstellung der IP-Adresse.
     */
    private function ipToBinary(string $ip): string
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