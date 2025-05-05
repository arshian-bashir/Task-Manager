@extends('layouts.app')
@section('title')
    {{ $project->name }} - Department Details
@endsection
@section('content')
    <div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white text-center"> {{ $project->name }}</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $project->name }}</h5>
                        <p class="card-text">{{ $project->description }}</p>

                        <h5 class="mt-4">Project Progress 
                        <span class="text-muted small">(Tasks completed out of Pending tasks)</span>
                        </h5>
                        @php
                            $totalTasks = $project->tasks->count();
                            $completedTasks = $project->tasks->where('status', 'completed')->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;"
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ round($progress) }}%</div>
                        </div>

                        <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to Departments</a>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title"> Team Members </h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addMemberModal"> <i class="bi bi-plus-circle"></i> </button>
                        </div>

                        <div class="row">
                            @foreach ($teamMembers as $user)
                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-12">
                                                <div class="card-body">
                                                    <p class="card-title fw-bolder">{{ $user->name }}</p>
                                                    <p class="card-text">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Task over due</h5>
                        <ul class="list-group flex-grow-1">
                        <table class="table table-bordered align-middle">
                            @foreach($tasksOverDue as $task)
                                <tbody>
                                    <tr>
                                        <td style="width: 62%; text-align: left;">
                                        <div class="d-flex justify-content-between align-items-start" style="flex-wrap: wrap;">
                                            <div style="flex: 1 1 0%; min-width: 0; word-wrap: break-word; white-space: normal;">
                                                <strong>{{ $task->title }}</strong>
                                            </div>
                                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary btn-sm ms-2 mt-1" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>

                                        </td>
                                        <td style="width: 10%; text-align: right;">
                                            <span class="badge 
                                                {{ $task->priority == 'low' ? 'bg-success' : 
                                                ($task->priority == 'medium' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                               {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td style="width: 10%; text-align: right;">
                                            @if ($task->status == 'completed')
                                                <span class="badge bg-success" title="Completed">Completed</span>
                                            @elseif ($task->status == 'to_do')
                                                <span class="badge bg-primary" title="To Do">To Do</span>
                                            @elseif ($task->status == 'in_progress')
                                                <span class="badge bg-warning text-dark" title="In Progress">In Progress</span>
                                            @else
                                                <span class="badge bg-secondary">{{ strtoupper(substr($task->status, 0, 1)) }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <span class="badge bg-secondary">
                                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M, Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                            </table>
                        </ul>
                    </div>
                </div>
            </div>
    </div>

    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">Add Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.addMember')}}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Select User</label>
                            <select class="form-select" name="user_id" id="">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
