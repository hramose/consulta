<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

class Office extends Model
{
    const DISTANCE_UNIT_KILOMETERS = 111.045;
    const DISTANCE_UNIT_MILES      = 69.0;

    protected $fillable = [
        'type','name','address','province','canton','district','city','phone','lat','lon','address_map','notification','notification_date','active'
    ];

    protected $appends = ['notification_datetime','notification_hour'];

    public function getNotificationDatetimeAttribute()
    {
        return Carbon::parse($this->notification_date)->format('Y-m-d');
    }
     public function getNotificationHourAttribute()
    {
        return Carbon::parse($this->notification_date)->toTimeString();
    }

     public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }
     public function scopeActive($query, $search)
    {
        
        return $query->where(function ($query) use ($search)
        {
           $query->where('active', $search );
           
        });
    }

     


    /**
     * @param $query
     * @param $lat
     * @param $lng
     * @param $radius numeric
     * @param $units string|['K', 'M']
     */
    public function scopeNearLatLng($query, $lat, $lng, $radius = 10, $units = 'K')
    {
        $distanceUnit = $this->distanceUnit($units);

        if (!(is_numeric($lat) && $lat >= -90 && $lat <= 90)) {
            throw new Exception("Latitude must be between -90 and 90 degrees.");
        }

        if (!(is_numeric($lng) && $lng >= -180 && $lng <= 180)) {
            throw new Exception("Longitude must be between -180 and 180 degrees.");
        }

        $haversine = sprintf('*, (%f * DEGREES(ACOS(COS(RADIANS(%f)) * COS(RADIANS(lat)) * COS(RADIANS(%f - lon)) + SIN(RADIANS(%f)) * SIN(RADIANS(lat))))) AS distance',
            $distanceUnit,
            $lat,
            $lng,
            $lat
        );

        $subselect = clone $query;
        $subselect
            ->selectRaw(\DB::raw($haversine));
       
        // Optimize the query, see details here:
        // http://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/

        $latDistance      = $radius / $distanceUnit;
        $latNorthBoundary = $lat - $latDistance;
        $latSouthBoundary = $lat + $latDistance;
        $subselect->whereRaw(sprintf("lat BETWEEN %f AND %f", $latNorthBoundary, $latSouthBoundary));

        $lngDistance     = $radius / ($distanceUnit * cos(deg2rad($lat)));
        $lngEastBoundary = $lng - $lngDistance;
        $lngWestBoundary = $lng + $lngDistance;
        $subselect->whereRaw(sprintf("lon BETWEEN %f AND %f", $lngEastBoundary, $lngWestBoundary));

        /*$query
            ->from(\DB::raw('(' . $subselect->toSql() . ') as d'))
            ->where('distance', '<=', $radius);*/
        $query->selectRaw(\DB::raw($haversine))
              //->whereRaw(sprintf("lat BETWEEN %f AND %f", $latNorthBoundary, $latSouthBoundary))
              //->whereRaw(sprintf("lon BETWEEN %f AND %f", $lngEastBoundary, $lngWestBoundary))
              ->having('distance', '<=', $radius);
           
    }

    /**
     * @param $units
     */
    private function distanceUnit($units = 'K')
    {
        if ($units == 'K') {
            return static::DISTANCE_UNIT_KILOMETERS;
        } elseif ($units == 'M') {
            return static::DISTANCE_UNIT_MILES;
        } else {
            throw new Exception("Unknown distance unit measure '$units'.");
        }
    }

     public function users()
    {
        return $this->belongsToMany(User::class);
    }
      public function verifiedUsers()
    {
        return $this->belongsToMany(User::class,'verified_offices');
    }
     public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function administrators()
    {
        return $this->users()->whereHas('roles', function ($query){
                        $query->where('name',  'clinica')
                             ->orWhere('name',  'asistente');
                    })->get();
    }
}
