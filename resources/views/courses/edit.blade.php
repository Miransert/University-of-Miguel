@extends('master')

@section('title', 'DataMatisk Semantik')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-6 offset-3">
                <div class="card">
                    <div class="card-header">Create Course</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('courses.show', $courses->id) }}" id="course-form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input name="name" class="form-control" type="text" value="{{$courses->name}}" id="name">
                                <div class="form-group mt-1">
                                    <label for="code">Code</label>
                                    <input name="code" class="form-control" type="text" value="{{$courses->code}}" id="code">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="ects">ECTS</label>
                                    <input name="ects" class="form-control" type="number" id="ects" value="{{$courses->ects}}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="faculty">Faculty</label>
                                    <select id="faculty" name="faculty" class="form-control">
                                        @foreach($selectFaculty as $faculty)
                                            <option value="{{ $faculty->id }}" {{ $courses->faculty_id == $faculty->id ? '':'selected' }}>
                                                {{ $faculty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control" name="description">{{$courses->description}}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

