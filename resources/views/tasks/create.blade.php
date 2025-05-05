@extends('layouts.app')
@section('title')
    Create Task
@endsection
@section('content')
    <div class="container">
        <h2 class="mb-4">Create Task</h2>

        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                <label for="status" class="form-label">Priority</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="to_do">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                @error('priority')
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
                <label for="project" class="form-label">Department</label>
                <select name="project" id="project" class="form-select" required>
                    <option value="" selected disabled>Select Department</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label for="userCheckboxes" class="form-label">Assign Users</label>
                <div id="userCheckboxes" class="p-3 border rounded" style="max-height: 300px; overflow-y: auto;">
                    <!-- User checkboxes will be inserted here -->
                </div>
            </div>

            <div class="mb-3" id="fileInputs">
                <div class="mb-3">
                    <label class="form-label">Choose File(s)</label>
                    <input type="file" name="file[]" class="form-control" multiple>
                </div>
            </div>

            <button type="button" id="addMoreFiles" class="btn btn-secondary">Add More Files</button><br><br>

            <button type="submit" class="btn btn-primary mb-3">Create Task</button>

            
        </form>
    </div>

<script>
    
document.addEventListener('DOMContentLoaded', function () {
    // Add More Files
    const addMoreBtn = document.getElementById('addMoreFiles');

        if (addMoreBtn) {
            addMoreBtn.addEventListener('click', function () {
                const fileInputs = document.getElementById('fileInputs');
                const allInputs = fileInputs.querySelectorAll('input[type="file"]');
                const lastInput = allInputs[allInputs.length - 1];

                if (lastInput && lastInput.files.length === 0) {
                    alert("Please choose a file in the previous input before adding another.");
                    return;
                }

                const div = document.createElement('div');
                div.className = 'mb-3';
                div.innerHTML = `
                    <label class="form-label">Choose More File(s)</label>
                    <input type="file" name="file[]" class="form-control" multiple>
                `;
                fileInputs.appendChild(div);
            });
        }

    // Department change
    const departmentSelect = document.getElementById('project');
    if (departmentSelect) {
        departmentSelect.addEventListener('change', function () {
            const departmentId = this.value;
            const container = document.getElementById('userCheckboxes');
            container.innerHTML = '';

            fetch(`/users-by-department/${departmentId}`)
                .then(response => response.json())
                .then(users => {
                    if (users.length === 0) {
                        container.innerHTML = '<p class="text-muted">No users found in this department.</p>';
                    } else {
                        users.forEach(user => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'form-check';

                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.name = 'user_id[]';
                            input.value = user.id;
                            input.id = 'user_' + user.id;
                            input.className = 'form-check-input';

                            const label = document.createElement('label');
                            label.className = 'form-check-label';
                            label.htmlFor = input.id;
                            label.textContent = user.name;

                            wrapper.appendChild(input);
                            wrapper.appendChild(label);
                            container.appendChild(wrapper);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<p class="text-danger">Failed to load users.</p>';
                });
        });
    }
});

</script>


@endsection
