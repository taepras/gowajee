<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Course;
use App\Section;
use App\User;
// use Request;
use Response;

class CoursesApiController extends Controller
{
    //
    public function getAllCourses()
    {
        return Course::with('sections')->get();
    }

    public function getCourse($id)
    {
        return Course::with('sections')->where('id', '=', $id)->get()[0];
    }

    public function getEnrolledCourses()
    {
        if(!Auth::check())
            return Response::json(['msg' => 'Not logged in'], 403);
        $user = Auth::user();
        return $user->courses()->with('course')->get();
    }

    public function getCoursesByTime($day, $time)
    {
        return Section::with('course')
            ->where('day', '=' ,$day)
            ->where('time', '=', $time)
            ->get();
    }

    public function register(Request $request)
    {
        if(!Auth::check())
            return Response::json(['msg' => 'Not logged in'], 403);
        $user = Auth::user();

        // get POST json request
        $courseId = $request->input('course');
        $sectionNumber = $request->input('section');
        if (!$courseId || !$sectionNumber)
            return Response::json(['msg' => 'Incorrect Format!'], 400);

        // check if the user has already enrolled course.
        $hasEnrolled = $user->courses()->pluck('course_id')->toArray();
        if (in_array($courseId, $hasEnrolled))
            return [
                'success' => false,
                'message' => 'Course already registered.'
            ];

        // do enroll
        $section = Section::where('course_id', '=', $courseId)
            ->where('section_number', '=', $sectionNumber)->get()[0];
        $user->courses()->attach($section->id);
        $user->save();
        return ['success' => true];
    }

    public function withdraw(Request $request)
    {
        if(!Auth::check())
            return Response::json(['msg' => 'Not logged in'], 403);
        $user = Auth::user();

        $courseId = $request->input('course');
        if (!$courseId)
            return Response::json(['msg' => 'Incorrect Format!'], 400);

        // check if the user has enrolled in the course.
        // FIXME should I remove this code? It doesn't do much...
        $hasEnrolled = $user->courses()->pluck('course_id')->toArray();
        if (!in_array($courseId, $hasEnrolled))
            return [
                'success' => false,
                'message' => 'Course not registered.'
            ];

        // do withdraw
        $sectionIds = Section::where('course_id', '=', $courseId)
            ->pluck('id')->toArray();
        if (count($sectionIds) <= 0)
            return ['success' => false];
        $user->courses()->detach($sectionIds);
        $user->save();
        return ['success' => true];
    }
}
