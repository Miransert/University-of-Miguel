<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Faculty;


class CourseController extends Controller
{
    //
    public function index(){
        $faculties = Faculty::all();
        return view('courses.index')->with('faculties', $faculties);
    }
    //
    public function showCourse(){
        return view('courses.show');
    }
    public function displayCreate(){
        return view('courses.create');
    }
    public function createCourse(Request $request){
        $course = new Course();
        $course->name = \request('name');
        $course->code = \request('code');
        $course->ects = \request('ects');
        $course->faculty_id = \request('faculty');
        $course->description = \request('description');
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'ects' => 'required|numeric',
            'description' => 'required',
        ]);
        $course->save();
        return redirect()->route('courses.show', [$course->id]);

        // create new course from the request
        // return user to /course/{course}

    }
    public function displayUpdate(){

    }
    public function updateCourse(){

    }
    public function deleteCourse(){

    }
}
