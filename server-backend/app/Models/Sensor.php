<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    /**
     * The table associated with this model
     *
     * @var string
     */
    protected $table = 'sensors';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The measurement data associated with this sensor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function measurements() {
        return $this->hasMany(SensorData::class);
    }
}
