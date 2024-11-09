<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['-100.5', -148.9],
            ['100', 212],
            ['100.5', 212.9],
            ['0.5', 32.9],
            ['9.9', 49.82],
            ['213.7', 416.66],
            ['-21.15', -6.07],
            ['21.15', 70.07],

        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($temperature, $expectedFahrenheit): void
    {
        $forecast = new Forecast();

        $forecast->setTemperature($temperature);
        $this->assertEquals($expectedFahrenheit, $forecast->getFahrenheit(), 'Expected $expectedFahrenheit F for $temperature C');
    }
}

