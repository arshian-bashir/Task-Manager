@extends('layouts.app')
@section('title')
    Tasks
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
        <div class="position-relative bg-white d-flex justify-content-end align-items-center mb-4 shadow-sm p-3 rounded">
            <h2 class="position-absolute start-50 translate-middle-x m-0">Tasks</h2>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary" 
                style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    Create Task
                </a>
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
                                    <p class="card-text"><strong>Department: </strong>{{ $task->project->name }}</p>
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
                        @foreach ($tasks['in_progress'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Department: </strong>{{ $task->project->name }}</p>
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
                        @foreach ($tasks['completed'] ?? [] as $task)
                            <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px;" class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                    </h5>
                                    <p class="card-text">{{ $task->description }}</p>
                                    <p class="card-text"><strong>Department: </strong>{{ $task->project->name }}</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const kanbanItems = document.querySelectorAll('.kanban-item');
            const kanbanLists = document.querySelectorAll('.kanban-list');
            const taskStatusInput = document.getElementById('task_status');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log('CSRF Token:', csrfToken);



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