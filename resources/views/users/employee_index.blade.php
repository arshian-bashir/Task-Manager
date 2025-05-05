@extends('employee_layouts.app')
@section('title')
    Employee Tasks
@endsection
@section('content')

<div class="container">    
    <div class="d-flex justify-content-between align-items-center bg-white mb-4 shadow-sm p-3 rounded">
        <h2>Users</h2>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="departmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Department
            </button>
            <ul class="dropdown-menu" aria-labelledby="departmentDropdown">
                @foreach ($projects as $project)
                    <li>
                        <a class="dropdown-item" href="#">{{ $project->name }}</a>
                    </li>
                @endforeach
            </u l>        
        </div>
    </div>
    <div id="userDetailsContainer">
        <p>Select a project to view users.</p>
    </div>
</div>


@endsection