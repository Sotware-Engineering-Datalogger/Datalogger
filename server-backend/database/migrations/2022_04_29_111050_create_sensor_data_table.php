<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('measurement_unit');
        });

        DB::table('sensors')->insert([
           ['name' => 'temperature', 'measurement_unit' => '°C'],
           ['name' => 'light_intensity', 'measurement_unit' => 'lux'],
           ['name' => 'atmospheric_pressure', 'measurement_unit' => 'Pa'],
           ['name' => 'humidity', 'measurement_unit' => '%'],
        ]);

        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sensor_id');
            $table->float('value');
            $table->timestamps();
            
            $table->foreign('sensor_id')->references('id')->on('sensors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_data');
        Schema::dropIfExists('sensors');
    }
};
