<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //
    protected $appends = ['enrolled'];

    public function getEnrolledAttribute()
    {
        // var_dump($this->hasMany(User::class));
        $students = $this->students();
        return $students ? $students->count() : 0;
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->belongsToMany('App\User', 'enrollments', 'section_id', 'user_id');
    }
}
