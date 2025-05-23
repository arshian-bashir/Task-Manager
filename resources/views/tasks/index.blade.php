@extends('layouts.app')
@section('title')
    {{ $project->name }} - Tasks
@endsection
@section('content')
    <style>
        .kanban-column {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            height: 100%;
        }

        .kanban-list {
            min-height: 200px;
            background-color: #f8f9fa;
            border: 2px dashed #ccc;
            padding: 10px;
            border-radius: 8px;
        }

        .kanban-item {
            cursor: move;
        }

        .kanban-item.invisible {
            opacity: 0.4;
        }

        .card-body .card-text {
            margin-bottom: 4px; 
        }
        
    </style>
    
    <div class="container">
        <div class="bg-white align-items-center mb-4 shadow-sm p-3 rounded">
            <h2 class="text-center">{{ $project->name }} - Tasks</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between bg-primary text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">To Do</h4>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createTaskModal"
                            data-status="to_do" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">+</button>
                    </div>
                    
                    <div class="kanban-list" id="to_do">
                        @foreach ($tasks['to_do'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $task->title }} 
                                        <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    
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
                        <button type="button" class="btn btn-light" data-bs-toggle="modal"
                            data-bs-target="#createTaskModal" data-status="in_progress"
                            style="padding-top: 0.5rem; padding-bottom: 0.5rem;">+</button>
                    </div>
                    
                    <div class="kanban-list" id="in_progress">
                        @foreach ($tasks['in_progress'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
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
                        <button type="button" class="btn btn-light" data-bs-toggle="modal"
                            data-bs-target="#createTaskModal" data-status="completed" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">+</button>
                    </div>
                    <div class="kanban-list" id="completed">
                        @foreach ($tasks['completed'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
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

        <!-- Create Task Modal -->
        <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control">
                                @error('due_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select name="priority" id="priority" class="form-select" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                @error('priority')
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

                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Assign Users
                                </button>
                                
                                <ul class="dropdown-menu p-3" aria-labelledby="userDropdown" style="max-height: 300px; overflow-y: auto;">
                                    
                                    @foreach ($users as $user)
                                   
                                        @if ($user->id != auth()->user()->id)
                                            <li class="form-check">
                                                <input type="checkbox" name="user_id[]" id="user_{{ $user->id }}" class="form-check-input" value="{{ $user->id }}">
                                                <label for="user_{{ $user->id }}" class="form-check-label">{{ $user->name }}</label>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                            <input type="hidden" name="status" id="task_status">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const kanbanItems = document.querySelectorAll('.kanban-item');
            const kanbanLists = document.querySelectorAll('.kanban-list');
            const createTaskModal = document.getElementById('createTaskModal');
            const taskStatusInput = document.getElementById('task_status');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log('CSRF Token:', csrfToken);

            createTaskModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; 
                var status = button.getAttribute('data-status'); 
                taskStatusInput.value = status;
            });

            kanbanItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            });

            kanbanLists.forEach(list => {
                list.addEventListener('dragover', handleDragOver);
                list.addEventListener('drop', handleDrop);
            });

            function handleDragStart(e) {
                e.dataTransfer.setData('text/plain', e.target.dataset.id);
                setTimeout(() => {
                    e.target.classList.add('invisible');
                }, 0);
            }

            function handleDragEnd(e) {
                e.target.classList.remove('invisible');
            }

            function handleDragOver(e) {
                e.preventDefault();
            }

            function handleDrop(e) {
                e.preventDefault();
                const id = e.dataTransfer.getData('text');
                const draggableElement = document.querySelector(`.kanban-item[data-id='${id}']`);
                let dropzone = e.target;

                while (dropzone && !dropzone.classList.contains('kanban-list')) {
                    dropzone = dropzone.parentElement;
                }        
                if (!dropzone) {
                    console.error('Dropzone not found');
                    return;
                }
                console.log('Dropzone:', dropzone);
        
                dropzone.appendChild(draggableElement);

                const status = dropzone.id;

                updateTaskStatus(id, status);
            }

            function updateTaskStatus(id, status) {
                fetch(`{{ url('projects/tasks/${id}/update-status') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken  
                    },
                    body: JSON.stringify({
                        status
                    })
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to update task status');
                    }
                    return response.json();
                }).then(data => {
                    console.log('Task status updated:', data);
                }).catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
@endsection