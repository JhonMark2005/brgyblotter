<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    public $timestamps = false;

    protected $fillable = ['type_name'];
}
