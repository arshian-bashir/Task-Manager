@extends('employee_layouts.app')
@section('title')
    Routines
@endsection
@section('content')

<div class="container">
        <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-3 rounded mb-4">
            <h2>Upcoming Routines</h2>
        </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2>Daily Routines</h2>
                    <div class="kanban-column">
                        @forelse($upcomingDailyRoutines as $routine)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><strong>{{ $routine->title }}</strong></h5>
                                    <p class="card-text">{{ $routine->description }}</p>
                                    <p class="card-text"><strong>Days:</strong>
                                    {{ implode(', ', array_map('ucfirst', json_decode($routine->days, true) ?? [])) }}
                                    </p>
                                    <p class="card-text"><strong>Department:</strong>
                                    {{ $routine->project->name ?? 'No Department' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p>No upcoming daily routines.</p>
                        @endforelse
                        <div class="mt-3">
                            <a href="{{ route('routines.showDaily') }}" class="btn btn-secondary">View All Daily
                                Routines</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2>Weekly Routines</h2>
                    <div class="kanban-column">
                        @forelse($upcomingWeeklyRoutines as $routine)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $routine->title }}</h5>
                                    <p class="card-text">{{ $routine->description }}</p>
                                    <p class="card-text"><strong>Days:</strong>
                                    {{ implode(', ', array_map('ucfirst', json_decode($routine->days, true) ?? [])) }}
                                    </p>
                                    <p class="card-text"><strong>Department:</strong>
                                    {{ $routine->project->name ?? 'No Department' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p>No upcoming daily routines.</p>
                        @endforelse
                        <div class="mt-3">
                            <a href="{{ route('routines.showDaily') }}" class="btn btn-secondary">View All Daily
                                Routines</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2>Monthly Routines</h2>
                    <div class="kanban-column">
                        @forelse($upcomingMonthlyRoutines as $routine)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $routine->title }}</h5>
                                    <p class="card-text">{{ $routine->description }}</p>
                                    <p class="card-text"><strong>Months:</strong>
                                    @php
                                    $months = json_decode($routine->months, true) ?? [];
                                    $monthNames = array_map(function($month) {
                                        return \Carbon\Carbon::create()->month((int) $month)->format('F');
                                    }, $months);
                                    @endphp

                                    {{ implode(', ', $monthNames) }}

                                    </p>
                                    <p class="card-text"><strong>Department:</strong>
                                    {{ $routine->project->name ?? 'No Department' }}
                                    </p>
                                </div>
                            </div>
                            @empty
                                <p>No upcoming daily routines.</p>
                            @endforelse
                        <div class="mt-3">
                            <a href="{{ route('routines.showDaily') }}" class="btn btn-secondary">View All Daily
                                Routines</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection