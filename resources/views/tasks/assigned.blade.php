@extends('layouts.app')
@section('title')
    All Task Details
@endsection
@section('content')
<div class="container">    
    <div class="row">
            <div class="col-md-4">
                <div class="kanban-list">
                    <div class="d-flex justify-content-between bg-primary text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">To Do</h4>
                    </div>
                    
                    <div class="kanban-list" id="to_do">
                        @foreach ($tasksList['to_do'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $task->title }} 
                                        <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p><strong>Department : </strong>{{ $task->project->name ?? 'No Department' }}</p>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between shadow-sm align-items-center bg-warning px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">In Progress</h4>
                    </div>
                    
                    <div class="kanban-list" id="in_progress">
                        @foreach ($tasksList['in_progress'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p><strong>Department : </strong>{{ $task->project->name ?? 'No Department' }}</p>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between shadow-sm align-items-center bg-success px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">Completed</h4>
                    </div>
                    <div class="kanban-list" id="completed">
                        @foreach ($tasksList['completed'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p><strong>Department : </strong>{{ $task->project->name ?? 'No Department' }}</p>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

</div>
@endsection