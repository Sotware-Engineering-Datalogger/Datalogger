<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SensorDataTest extends TestCase
{
    /**
     * Test if a get request to /sensor/temperature is successful.
     *
     * @return void
     */
    public function test_get_temperature_data()
    {
        $response = $this->get('/sensor/temperature');

        $response->assertStatus(200);
    }

    /**
     * Test if a get request to /sensor/humidity is successful.
     *
     * @return void
     */
    public function test_get_humidity_data()
    {
        $response = $this->get('/sensor/humidity');

        $response->assertStatus(200);
    }

    /**
     * Test if a get request to /sensor/pressure is successful.
     *
     * @return void
     */
    public function test_get_pressure_data()
    {
        $response = $this->get('/sensor/pressure');

        $response->assertStatus(200);
    }

    /**
     * Test if a get request to /sensor/all is successful.
     *
     * @return void
     */
    public function test_get_light_data()
    {
        $response = $this->get('/sensor/light');

        $response->assertStatus(200);
    }

}
