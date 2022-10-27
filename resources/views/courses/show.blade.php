@extends('master')

@section('title', 'DataMatisk Semantik')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-8">
                <h1 class="display-6">DataMatisk Semantik</h1>
                <p class="h4 text-secondary">DaMa - 20 ECTS</p>
                <p>Spicy jalapeno veniam sausage turducken, sed ham hock ball tip tempor filet mignon excepteur boudin.
                    Ham hock bacon labore reprehenderit in alcatra chicken doner hamburger. Capicola venison chuck lorem
                    doner t-bone. Rump velit picanha corned beef labore. Labore leberkas dolor pancetta. Short ribs id
                    tenderloin, ullamco dolore occaecat prosciutto enim quis incididunt tempor meatloaf nulla.</p>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        Faculty of Engineering (TEK)
                    </div>
                    <div class="card-body">
                        <p class="card-text">Voluptas velit reiciendis eum est et. Vitae aut provident rerum rem dolores
                            sed voluptatem. Accusantium aut est non ipsum. Ab libero assumenda autem aut ut qui voluptas
                            non. Omnis placeat qui sed eos sint.</p>
                    </div>
                </div>
                <div class="mt-3 d-grid gap-3">
                    <a href="#" class="btn btn-primary" id="edit-course">Edit course</a>
                    <form>
                        <button type="submit" class="btn btn-danger w-100" id="delete-course">Delete course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

