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
     * Test if a get request to /sensor/light is successful.
     *
     * @return void
     */
    public function test_get_light_data()
    {
        $response = $this->get('/sensor/light');

        $response->assertStatus(200);
    }

    /**
     * Test if new temperature data is stored in the database.
     *
     * @return void
     */
    public function test_store_temperature_data()
    {
        $response = $this->postJson('/sensor/temperature', [
            'value' => '25'
        ]);

        $response->assertStatus(201)->assertExactJson([
                'created' => true,
        ]);
    }

    /**
     * Test if new humidity data is stored in the database.
     *
     * @return void
     */
    public function test_store_humidity_data()
    {
        $response = $this->postJson('/sensor/humidity', [
            'value' => '15'
        ]);

        $response->assertStatus(201)->assertExactJson([
                'created' => true,
        ]);
    }

    /**
     * Test if new pressure data is stored in the database.
     *
     * @return void
     */
    public function test_store_pressure_data()
    {
        $response = $this->postJson('/sensor/pressure', [
            'value' => '30'
        ]);

        $response->assertStatus(201)->assertExactJson([
                'created' => true,
        ]);
    }

    /**
     * Test if new light data is stored in the database.
     *
     * @return void
     */
    public function test_store_light_data()
    {
        $response = $this->postJson('/sensor/light', [
            'value' => '10'
        ]);

        $response->assertStatus(201)->assertExactJson([
                'created' => true,
        ]);
    }

    /**
     * Test if a post request with invalid data is rejected.
     *
     * @return void
     */
    public function test_store_temperature_data_with_invalid_value()
    {
        $response = $this->postJson('/sensor/temperature', [
            'value' => 'invalid'
        ]);

        $response->assertStatus(422)->assertExactJson([
                'created' => false,
        ]);
    }

    /**
     * Test if a post request with invalid data is rejected.
     *
     * @return void
     */
    public function test_store_humidity_data_with_invalid_value()
    {
        $response = $this->postJson('/sensor/humidity', [
            'value' => 'invalid'
        ]);

        $response->assertStatus(422)->assertExactJson([
                'created' => false,
        ]);
    }

    /**
     * Test if a post request with invalid data is rejected.
     *
     * @return void
     */
    public function test_store_pressure_data_with_invalid_value()
    {
        $response = $this->postJson('/sensor/pressure', [
            'value' => 'invalid'
        ]);

        $response->assertStatus(422)->assertExactJson([
                'created' => false,
        ]);
    }

    /**
     * Test if a post request with invalid data is rejected.
     *
     * @return void
     */
    public function test_store_light_data_with_invalid_value()
    {
        $response = $this->postJson('/sensor/light', [
            'value' => 'invalid'
        ]);

        $response->assertStatus(422)->assertExactJson([
                'created' => false,
        ]);
    }
}
