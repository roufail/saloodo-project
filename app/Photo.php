<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $guarded = [];
    public function Photoable() {
        return $this->morghTo();
    }
}
