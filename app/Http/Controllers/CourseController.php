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
        $courses = Course::all();
        return view('courses.index')->with('faculties', $faculties)->with('courses', $courses);
    }
    //
    public function showCourse($id){
        $faculties = Faculty::findOrFail($id);
        $courses = Course::findOrFail($id);
        return view('courses.show')->with('faculties', $faculties)->with('courses', $courses);
    }
    public function displayCreate(){
        $faculties = Faculty::all();
        return view('courses.create')->with('faculties', $faculties);
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
            'faculty' => 'exists:App\Models\Faculty,id',
            'description' => 'required'
        ]);
        $course->save();
        return redirect()->route('courses.show', [$course->id]);

        /*
         * @foreach($faculties as $faculty)
         *      <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
         * @endforeach
        */

        // create new course from the request
        // return user to /course/{course}

    }
    public function displayUpdate($id){
        $selectFaculty = Faculty::all();
        $courses = Course::findOrFail($id);
        return view('courses.edit')->
            with('courses', $courses)->with('selectFaculty', $selectFaculty);
    }
    public function updateCourse(Request $request, $id){

        $course = Course::find($id);
        $course->name = \request('name');
        $course->code = \request('code');
        $course->ects = \request('ects');
        $course->faculty_id = \request('faculty');
        $course->description = \request('description');

        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'ects' => 'required|numeric',
            'faculty' => 'exists:App\Models\Faculty,id',
            'description' => 'required'
        ]);

        $course->update();
        return redirect()->route('courses.show', [$course->id]);
    }
    public function deleteCourse($id){
        $course = Course::find($id);
        $course->delete();
        return redirect('/');
    }
}
