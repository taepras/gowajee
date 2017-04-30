<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    // protected $visible = ['sections'];
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    protected $appends = ['is_enrolled'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function getIsEnrolledAttribute($user_id) {
        $sections = $this->hasMany(Section::class);
        $enrolled = false;
        for ($i = 0; $i < $sections->count(); $i++) {
            if ($sections->get()[$i]->getIsEnrolledAttribute()) {
                $enrolled = true;
                break;
            }
        }
        return $enrolled;
    }
}
