<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
            })->values(),
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

    /**
     * Store a new temperature measurement.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTemperature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['created' => false], 422);
        }

        $sensor = Sensor::where('name', 'temperature')->first();
        $sensor->measurements()->create([
            'value' => $request->value,
        ]);

        return response()->json(['created' => true], 201);
    }

    /**
     * Store a new humidity measurement.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeHumidity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['created' => false], 422);
        }

        $sensor = Sensor::where('name', 'humidity')->first();
        $sensor->measurements()->create([
            'value' => $request->value,
        ]);

        return response()->json(['created' => true], 201);
    }

    /**
     * Store a new atmospheric pressure measurement.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePressure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['created' => false], 422);
        }

        $sensor = Sensor::where('name', 'atmospheric_pressure')->first();
        $sensor->measurements()->create([
            'value' => $request->value,
        ]);

        return response()->json(['created' => true], 201);
    }

    /**
     * Store a new light intensity measurement.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['created' => false], 422);
        }

        $sensor = Sensor::where('name', 'light_intensity')->first();
        $sensor->measurements()->create([
            'value' => $request->value,
        ]);

        return response()->json(['created' => true], 201);
    }
}
