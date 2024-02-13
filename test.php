<?php

namespace Kaas;

class Kaas
{
    public int $id;
}

namespace Test;

use DateTime;
use Kaas\Kaas;
use ReflectionClass;

class Test
{
    public int $id;
    public string $test = "hoi";
    public array $kaas;

    /**
     * @param string $test
     */
    public function __construct(string $test)
    {
        $this->test = $test;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return "test";
    }

    /**
     * @return array
     */
    public function getKaas(): array
    {
        return $this->kaas;
    }

    public function addKaas(Kaas $kaas): void
    {
        $this->kaas[] = $kaas;
    }

    public function deleteKaas(Kaas $kaas): void
    {
        $this->kaas = array_filter($this->kaas, function ($obj) use ($kaas) {
            return $obj !== $kaas;
        });
    }




}

$date1 = "10-10-2023 16:00";
$date2 = "10-10-2023 15:00";
try {
    $date = new DateTime($date1);
    $now = new DateTime($date2);

    var_dump($date<$now);
} catch (\Exception $e) {
}


