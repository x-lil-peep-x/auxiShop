<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarLine extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'car_lines';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the spares for this model.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function spares()
    {
        return $this->belongsToMany('App\Models\Spare','spare_car_line','car_line_id', 'spare_id');
    }




}
