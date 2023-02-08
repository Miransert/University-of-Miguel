@extends('master')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-4">
                <ul class="list-group">
                    @foreach($faculties as $faculty)
                        <li class="list-group-item">{{ $faculty->name }}</li>
                    @endforeach
                </ul>
                <div class="mt-3 d-grid">
                    <a href="{{ route('create') }}" class="btn btn-success" id="create-course">Create Course</a>
                </div>
            </div>
            <div class="col">
                <div class="row gap-3">
                    @foreach ($courses as $course)
                    <div class="card course">
                        <div class="card-body">
                            <h5 class="card-title">{{$course->name}}</h5>
                            <h6 class="card-subtitle mb-2 text-muted" value="{{$course->faculty->id}}">{{$course->faculty->name}}</h6>
                            <p class="card-text">{{$course->description}}</p>
                            <a href="{{ route('courses.show', $course->id) }}" class="card-link course-details">Details</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
