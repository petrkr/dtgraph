<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Sensor extends Model
{
    protected $table = 'digitemp_metadata';
    protected $primaryKey = 'SerialNumber';
    public $timestamps = false;
    //


    public static function read($sensor) {
        $key = "sensor_metadata";

        $result = null;
        if (Cache::has($key)) {
            $result = Cache::get($key);
        } else {

            //using either query builder or the eloquent stuff fails as it converts SerialNumber to integer.
            if (isset($sensor)) {
                $result = DB::select('select * from digitemp_metadata where SerialNumber = ?', [$sensor]);
            } else {
                $result = DB::select('select * from digitemp_metadata');
            }

//            $result = Sensor::all();
            $expiresAt = Carbon::now()->addMinutes(10); //TODO: unharcode
            Cache::put($key, $result, $expiresAt);
        }

        return $result;
    }

}
