@extends('employee_layouts.app')
@section('title')
    Departments
@endsection
@section('content')

<div class="container">
        <div class="d-flex justify-content-between align-items-center bg-white mb-4 shadow-sm p-3 rounded">
            <h2>Departments</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @foreach($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $project->name }}</h5>
                            <p class="card-text">{{ Str::limit($project->description, 250) }}</p>
                            <a href="{{ route('projects.employee_show', ['id' => $project->id]) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection