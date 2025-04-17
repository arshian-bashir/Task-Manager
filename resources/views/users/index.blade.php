@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center bg-white mb-4 shadow-sm p-3 rounded">
        <h2>Users</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
    </div>
    <div class="row">
        <div class="kanban-column">
            <div class="col-md-8">
                <div class="d-flex justify-content-between bg-primary text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                    <h4 class="text-white fw-bolder m-0">Users</h4>
                </div>

                <div class="kanban-list">
                        @foreach ($users as $user)
                            <div class="card mb-3 kanban-item" data-id="{{ $user->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $user->name }} 
                                    </h5>
                                    <p><strong>Department : </strong>{{ $user->project->name ?? 'No Department' }}</p>
                                    <p><strong>Email : </strong>{{ $user->email }}</p>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                                </div>
                            </div>
                        @endforeach
                </div>

            </div>
        </div>
    </div>
</div>


@endsection
