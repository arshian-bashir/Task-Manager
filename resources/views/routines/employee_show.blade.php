@extends('employee_layouts.app')
@section('title')
    Employee Tasks
@endsection
@section('content')

<div class="container"> 
    <h2 class="mb-4 shadow-sm p-3 rounded bg-white text-center">{{ $routine->title }} - Routine Details</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-12 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $routine->title }}</h5>
                            <p class="card-text">{{ $routine->description }}</p>
                            <p class="card-text"><strong>Frequency: </strong>{{ \Illuminate\Support\Str::ucfirst($routine->frequency) }}</p>
                            @if($routine->frequency === 'daily')
                                <p class="card-text mb-0"><strong>Days:</strong></p>
                                <ul>
                                    @foreach(json_decode($routine->days, true) ?? [] as $day)
                                        <li>{{ ucfirst($day) }}</li>
                                    @endforeach
                                </ul>                                
                            @elseif($routine->frequency === 'weekly')
                                <p class="card-text"><strong>Weeks: </strong>{{ $routine->weeks }}</p>
                            @elseif($routine->frequency === 'monthly')
                                <p class="card-text mb-0"><strong>Months:</strong></p>
                                <ul>
                                    @foreach(json_decode($routine->months, true) ?? [] as $month)
                                        <li>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#editRoutineModal"> <i class="bi bi-pencil-square"></i> 
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="editRoutineModal" tabindex="-1" aria-labelledby="editRoutineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoutineModalLabel">Edit Routine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee_routines.update', $routine->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Title -->
                    <input type="hidden" name="project" value="{{ $routine->project_id }}">

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $routine->title }}" required>
                    </div>
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $routine->description }}</textarea>
                    </div>
                    <!-- Frequency -->
                    <div class="mb-3">
                        <label for="frequency" class="form-label">Frequency</label>
                        <select class="form-select" id="frequency" name="frequency" required>
                            <option value="daily" {{ $routine->frequency === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $routine->frequency === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $routine->frequency === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>

                    <!-- Days Selection -->
                    <div class="mb-3" id="days" style="{{ $routine->frequency == 'daily' ? '' : 'display: none;' }}">
                        <label class="form-label">Select Days</label>
                        @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="{{ $day }}" id="{{ $day }}"
                                    {{ in_array($day, json_decode($routine->days, true) ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $day }}">{{ ucfirst($day) }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Weeks Selection -->
                    <div class="mb-3" id="weeks" style="{{ $routine->frequency == 'weekly' ? '' : 'display: none;' }}">
                        <label class="form-label">Select Weeks</label>
                        @for ($i = 1; $i <= 52; $i++)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="weeks[]" value="{{ $i }}" id="week{{ $i }}"
                                    {{ in_array($i, json_decode($routine->weeks, true) ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="week{{ $i }}">Week {{ $i }}</label>
                            </div>
                        @endfor
                    </div>

                    <!-- Months Selection -->
                    <div class="mb-3" id="months" style="{{ $routine->frequency == 'monthly' ? '' : 'display: none;' }}">
                        <label class="form-label">Select Months</label>
                        @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="months[]" value="{{ $index + 1 }}" id="month{{ $index + 1 }}"
                                    {{ in_array($index + 1, json_decode($routine->months, true) ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="month{{ $index + 1 }}">{{ $month }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Update Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const frequencyField = document.getElementById('frequency');
        const daysField = document.getElementById('days');
        const weeksField = document.getElementById('weeks');
        const monthsField = document.getElementById('months');

        // Update visibility when the frequency changes
        frequencyField.addEventListener('change', function () {
            const frequency = this.value;

            // Show or hide fields based on frequency
            daysField.style.display = (frequency === 'daily') ? '' : 'none';
            weeksField.style.display = (frequency === 'weekly') ? '' : 'none';
            monthsField.style.display = (frequency === 'monthly') ? '' : 'none';
        });

        // Initialize visibility based on current frequency
        frequencyField.dispatchEvent(new Event('change'));
    });
</script>
@endsection