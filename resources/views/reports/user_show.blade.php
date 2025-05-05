@extends('layouts.app')
@section('title')
    Reports
@endsection
@section('content')
<style>
    .shaded-row {
        background-color: #f8f9fa; 
        margin-top: 30px; 
    }

    .card-body .card-text {
        margin-bottom: 4px; 
    }
        
</style>
<div class="container">
    <div class="bg-white mb-4 shadow-sm p-3 rounded">
        <h3 class="mb-4"><strong>Name : </strong>{{ $user->name }}</h3>
        <h6 class="mb-0 text-muted"><strong>Department : </strong>{{ $project->name }}</h6>
        <h6 class="mb-0 text-muted"><strong>Email : </strong>{{ $user->email }}</h6>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Total Tasks</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ $tasks->count() }}</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">To Do</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ $tasksToDo->count() }}</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">In Progress</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ $tasksInProgress->count() }}</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Completed</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ $tasksCompleted->count() }}</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Tasks Completed After Due Date</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ number_format($latenessPercentage, 2) }} </strong>%</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row shaded-row">
            <div class="col-md-4">
                <div class="kanban-list">
                    <div class="d-flex justify-content-between bg-primary text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">To Do</h4>
                    </div>
                    <div class="kanban-list" id="to_do">
                        @foreach ($tasksToDo as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $task->title }} 
                                        <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p><strong>Department : </strong>{{ $task->project->name ?? 'No Department' }}</p>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                    <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>
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
                        @foreach ($tasksInProgress as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p><strong>Department : </strong>{{ $task->project->name ?? 'No Department' }}</p>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                    <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>
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
                        @foreach ($tasksCompleted as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p><strong>Department : </strong>{{ $task->project->name ?? 'No Department' }}</p>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                    <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>
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