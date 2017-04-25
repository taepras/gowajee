<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    // protected $visible = ['sections'];
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
