<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['bundle_id','products'];
}
