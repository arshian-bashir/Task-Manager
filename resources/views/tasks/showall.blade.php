@extends('layouts.app')
@section('title')
    All Task Details
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="kanban-column">
            <div class="col-md-6">
                    <div class="d-flex justify-content-between bg-primary text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">Tasks Assigned to me</h4>
                        
                    </div>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">

                            @foreach ($userTasks as $task)
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <p class="card-text"><strong>Project:</strong> {{ $task->project->name ?? 'No Project' }}</p>
                            <p class="card-text">{{ $task->description }}</p>
                                <p class="card-text"><strong>Due Date:</strong> {{ $task->due_date }}</p>
                                <p class="card-text"><strong>Priority:</strong> <span
                                    class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                            </p>

                            <p class="card-text"><strong>Status:</strong>
                                        @if ($task->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($task->status == 'to_do')
                                            <span class="badge bg-primary">To Do</span>
                                        @elseif($task->status == 'in_progress')
                                            <span class="badge bg-warning">In Progress</span>
                                        @endif
                                    </p>

                                    <p class="card-text"><strong>Assign To:</strong> 
                                        @php
                                            $userIds = explode(',', $task->user_id);
                                            $users = \App\Models\User::whereIn('id', $userIds)->get();
                                        @endphp

                                        @if($users->isNotEmpty())
                                            @foreach($users as $user)
                                                <span class="badge bg-info">{{ $user->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
