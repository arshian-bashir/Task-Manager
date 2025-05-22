@extends('employee_layouts.app')
@section('title')
    Employee Tasks
@endsection
@section('content')
<style>
    .card-body .card-text {
        margin-bottom: 4px; 
    }
</style>
<div class="container">    
    <div class="d-flex justify-content-between align-items-center bg-white mb-4 shadow-sm p-3 rounded">
        <h2>Tasks</h2>
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            Add Task
        </a>
    </div>
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
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                    <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>
                                    <a href="{{ route('tasks.employee_task_show', $task->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
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
                    
                    <div class="kanban-list" id="to_do">
                        @foreach ($tasksList['in_progress'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $task->title }} 
                                        <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                    <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>
                                    <a href="{{ route('tasks.employee_task_show', $task->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
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
                    <div class="kanban-list" id="to_do">
                        @foreach ($tasksList['completed'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $task->title }} 
                                        <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                    <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>
                                    <a href="{{ route('tasks.employee_task_show', $task->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
</div>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tasks.employee_task_create', $project->id) }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="to_do">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Assign Users
                                </button>
                                
                                <ul class="dropdown-menu p-3" aria-labelledby="userDropdown" style="max-height: 300px; overflow-y: auto;">

                                    
                                    @foreach ($projectUsers as $user)
                                   
                                        <li class="form-check">
                                            <input type="checkbox" name="user_id[]" id="user_{{ $user->id }}" class="form-check-input" value="{{ $user->id }}">
                                            <label for="user_{{ $user->id }}" class="form-check-label">{{ $user->name }}</label>
                                        </li>
                                            
                                    @endforeach
                                </ul>
                            </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
