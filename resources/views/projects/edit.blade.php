@extends('layouts.app')
@section('title')
    {{ $project->name }} Edit Department
@endsection
@section('content')
    <div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white">Edit Department</h2>
        <div class="card border-0 shadow-sm m-auto" style="max-width: 600px;">
            <div class="card-body">
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $project->name }}"
                            required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ $project->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="head_of_department" class="form-label">Head of Department</label>
                        <select name="head_of_department" id="head_of_department" class="form-select select2" required>
                            <option value="{{ $project->hod_id }}" disabled selected>
                                {{ $users->firstWhere('id', $project->hod_id)?->name ?? 'Select a user' }}
                            </option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $project->hod_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('head_of_department')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <button type="submit" class="btn btn-primary">Update Department</button>
                </form>
            </div>
        </div>
    </div>
@endsection
