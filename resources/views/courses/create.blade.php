@extends('master')

@section('title', 'DataMatisk Semantik')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-6 offset-3">
                <div class="card">
                    <div class="card-header">Create Course</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('index') }}" id="course-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input name="name" class="form-control" type="text" value="" id="name">
                                <div class="form-group mt-1">
                                    <label for="code">Code</label>
                                    <input name="code" class="form-control" type="text" value="" id="code">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="ects">ECTS</label>
                                    <input name="ects" class="form-control" type="number" id="ects" value="">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="faculty">Faculty</label>
                                    <select id="faculty" name="faculty" class="form-control">
                                        <option value="1">Faculty of Business and Social Sciences</option>
                                        <option value="2">Faculty of Engineering</option>
                                        <option value="3">Faculty of Health Sciences</option>
                                        <option value="4">Faculty of Humanities</option>
                                        <option value="5">Faculty of Science</option>
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control" name="description"></textarea>
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

