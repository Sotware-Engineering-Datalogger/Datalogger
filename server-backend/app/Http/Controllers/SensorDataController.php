<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SensorDataController extends Controller
{
    /**
     * Get the measurement data for a sensor.
     *
     * @param String $sensorName
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getSensorData(String $sensorName, Request $request): \Illuminate\Support\Collection
    {
        $sensor = Sensor::where('name', $sensorName)->first();
        if ($request->from != null && $request->to != null) {
            $from_timestamp = Carbon::parse($request->from);
            $to_timestamp = Carbon::parse($request->to);
            $data = $sensor->measurements->whereBetween('created_at', [$from_timestamp, $to_timestamp]);
        }
        else {
            $data = $sensor->measurements;
        }

        return collect([
            'data' => $data->map(function ($item) use($sensor) {
                return [
                    'value' => $item->value,
                    'created_at' => $item->created_at->toIso8601String(),
                ];
            }),
            'sensor' => $sensor->name,
            'unit' => $sensor->measurement_unit,
        ]);
    }

    /**
     * Return the requested temperature data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexTemperature(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getSensorData('temperature', $request), 200);
    }

    /**
     * Return the requested humidity data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexHumidity(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getSensorData('humidity', $request), 200);
    }

    /**
     * Return the requested atmospheric pressure data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexPressure(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getSensorData('atmospheric_pressure', $request), 200);
    }

    /**
     * Return the requested light intensity data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexLight(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getSensorData('light_intensity', $request), 200);
    }

}
