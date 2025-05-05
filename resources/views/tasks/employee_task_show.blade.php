@extends('employee_layouts.app')
@section('title')
    Employee Tasks
@endsection
@section('content')

<div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white text-center">{{ $task->title }} - Task Details</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <p class="card-text"><strong>Creation Date: </strong>{{ \Carbon\Carbon::parse($task->created_at)->format('F d, Y') }}</p>
                                <p class="card-text"><strong>Due Date: </strong>{{ \Carbon\Carbon::parse($task->due_date)->format('F d, Y') }}</p>  
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
                                @if($files->isNotEmpty())
                                    <p class="card-text"><strong>File:</strong>
                                    
                                        @foreach($files as $file)
                                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank">{{ $file->name }}</a><br>
                                        @endforeach
                                    </p>
                                @else
                                    <p class="card-text text-muted">No File Uploaded</p>
                                @endif
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#editTaskModal-{{ $task->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                </button>

                            </div>
                            <div class="col-md-6 border-start">
                                <h5>Comments</h5>
                                <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($messages as $message)
                                        <div class="border p-2 mb-2 rounded bg-light">
                                            <p class="mb-0"><strong>{{ $message->user->name ?? 'Unknown User' }} :</strong>
                                                {{ $message->message }}</p>
                                            <small class="text-muted">{{ $message->created_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                    @empty
                                        <p class="text-muted">No comments yet.</p>
                                    @endforelse
                                </div>
                                <div class="mt-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addCommentModal-{{ $task->id }}">
                                        <i class="bi bi-chat-left-text"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>




<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal-{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel-{{ $task->id }}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tasks.employee_update', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $task->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel-{{ $task->id }}">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title-{{ $task->id }}" class="form-label">Title</label>
                        <input type="text" name="title" id="title-{{ $task->id }}" class="form-control"
                            value="{{ $task->title }}" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description-{{ $task->id }}" class="form-label">Description</label>
                        <textarea name="description" id="description-{{ $task->id }}" class="form-control">{{ $task->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="due_date-{{ $task->id }}" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date-{{ $task->id }}" class="form-control"
                            value="{{ $task->due_date }}">
                        @error('due_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="priority-{{ $task->id }}" class="form-label">Priority</label>
                        <select name="priority" id="priority-{{ $task->id }}" class="form-select" required>
                            <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status-{{ $task->id }}" class="form-label">Status</label>
                        <select name="status" id="status-{{ $task->id }}" class="form-select" required>
                            <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose File</label>
                        <input type="file" name="file" id="file" class="form-control">
                        @error('file')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Add Comment Modal -->
<div class="modal fade" id="addCommentModal-{{ $task->id }}" tabindex="-1" aria-labelledby="addCommentModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tasks.employee_commment_update') }}" method="POST">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentModalLabel-{{ $task->id }}">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message" class="form-label">Comment</label>
                        <textarea class="form-control" name="message" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection