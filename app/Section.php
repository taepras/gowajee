<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Section extends Model
{
    //
    protected $appends = ['enrolled', 'is_enrolled'];

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

    public function asCourse()
    {
        $course = $this->course()->with('sections');
        $course->where('section_id', '=', $this->id);
        return;
    }

    public function getIsEnrolledAttribute () {
        return !!$this->students()->find(Auth::user()->id);
    }
}
