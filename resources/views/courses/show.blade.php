@extends('master')

@section('title', 'DataMatisk Semantik')

@section('content')

    <div class="container mt-4">
        <div class="row">
            <div class="col-8">
                <h1 class="display-6">{{$courses->name}}</h1>
                <p class="h4 text-secondary">{{$courses->code}} - {{$courses->ects}} ECTS</p>
                <p>{{$courses->description}}</p>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        {{$courses->faculty->name}} ({{$courses->faculty->code}})
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{$courses->faculty->description}}</p>
                    </div>
                </div>
                <div class="mt-3 d-grid gap-3">
                    <a href="{{ route('courses.edit', $courses->id) }}" class="btn btn-primary" id="edit-course">Edit course</a>
                    <form action="{{ route('delete.course', $courses->id) }}" method="POST">
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" id="delete-course">Delete course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

