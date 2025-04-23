@extends('employee_layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')

<style>
    #DonutChart1 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        width: 100%;
    }

    .chart-legend-container {
        flex: 1 1 120px;
    }

    .chart-legend div {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .chart-legend span {
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 8px;
        border-radius: 50%;
    }

    #myDonutChart1 {
        flex: 1 1 200px;
        max-width: 100%;
        height: auto;
        max-height: 230px;
    }
</style>
    <div class="container">
        <h2>Welcome to your Employee Dashboard</h2>
        <p>This is your dashboard where you can manage your tasks, routines, notes, and files.</p>
        
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tasks</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $tasksCount }}</strong> tasks pending.</p>
                        <a href="{{ route('tasks.employee_show') }}" class="btn btn-primary mt-auto">View my Tasks</a>
                        <br>
                        <a href="{{ route('employee_depart_tasks') }}" class="btn btn-primary mt-auto">View Tasks assigned to {{ $userProject }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Routines</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $dailyRoutinesCount }}</strong> daily routines scheduled today.</p>
                        <p class="card-text flex-grow-1">You have <strong>{{ $monthlyRoutinesCount }}</strong> monthly routines scheduled this monthly.</p>
                        <a href="{{ route('routines.employee_index') }}" class="btn btn-primary mt-auto">View Routines</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Notes</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $notesCount }}</strong> notes saved.</p>
                        <a href="#" class="btn btn-primary mt-auto">View Notes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Files</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $filesCount }}</strong> files.</p>
                        <a href="#" class="btn btn-primary mt-auto">View Files</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Recent Tasks</h5>
                        <ul class="list-group flex-grow-1">
                            
                        <li class="list-group-item">
                            <table class="table table-bordered text-start align-middle mb-0">
                                <thead class="table-light fw-bold">
                                    <tr>
                                        <th style="width: 24%;">Title</th>
                                        <th style="width: 60%;">Description</th>
                                        <th style="width: 8%;">Priority</th>
                                        <th style="width: 8%;">Status</th>
                                        <th style="width: 8%;">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allTasks->sortBy('due_date') as $task)
                                        <tr>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $task->title }}</strong>
                                                <a href="{{ route('tasks.employee_task_show', $task->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                            <td>{{ $task->description }}
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    {{ $task->priority == 'low' ? 'bg-success' : 
                                                    ($task->priority == 'medium' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                            <td>
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
                                            <td>
                                                <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($task->due_date)->format('d M, Y') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tasks Due today</h5>
                        <ul class="list-group flex-grow-1">
                            <table class="table table-bordered align-middle">
                            @foreach($dueToday as $task)
                                <tbody>
                                    <tr>
                                        <td  style="width: 62%; text-align: left;">
                                            <div class="d-flex justify-content-between align-items-start" style="flex-wrap: wrap;">
                                                <div style="flex: 1 1 0%; min-width: 0; word-wrap: break-word; white-space: normal;">
                                                    <strong>{{ $task->title }}</strong>
                                                </div>
                                                <a href="{{ route('tasks.employee_task_show', $task->id) }}" class="btn btn-primary btn-sm ms-2 mt-1" target="_blank">
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
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Task over due</h5>
                        <ul class="list-group flex-grow-1">
                        <table class="table table-bordered align-middle">
                            @foreach($overdueTasks as $task)
                                <tbody>
                                    <tr>
                                        <td style="width: 62%; text-align: left;">
                                        <div class="d-flex justify-content-between align-items-start" style="flex-wrap: wrap;">
                                            <div style="flex: 1 1 0%; min-width: 0; word-wrap: break-word; white-space: normal;">
                                                <strong>{{ $task->title }}</strong>
                                            </div>
                                            <a href="{{ route('tasks.employee_task_show', $task->id) }}" class="btn btn-primary btn-sm ms-2 mt-1" target="_blank">
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
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body flex-column">
                        <h5 class="card-title">Today's Daily Routines</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($todayRoutines as $routine)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $routine->title }}
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary rounded-pill">{{ $routine->frequency }}</span>
                                        <a href="{{ route('routines.employee_show', $routine->id) }}" class="btn btn-primary" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <br>

                        <h5 class="card-title">This month's Monthly Routines</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($monthRoutines as $routine)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $routine->title }}
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary rounded-pill">{{ $routine->frequency }}</span>
                                        <a href="{{ route('routines.employee_show', $routine->id) }}" class="btn btn-primary" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Count of tasks as per status</h5>
                        <div id="DonutChart1">
                            <div class="chart-legend-container">
                                <div id="donutLegend1" class="chart-legend">

                                </div>
                            </div>
                            <canvas id="myDonutChart1"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const donutCtx1 = document.getElementById('myDonutChart1');

        if (!donutCtx1) {
            console.error('Canvas element not found');
            return;
        }
        
        const donutData1 = {
            labels: ['To do', 'In progress', 'Completed'],
            datasets: [{
                label: ' Count ',
                data: @json($statusesCount->values()),
                backgroundColor: ["#42A5F5", "#FFEB3B", "#66BB6A"],
                hoverBackgroundColor: ["#3498DB", "#F1C40F", "#4CAF50"],
                borderWidth: 3
            }]
        };

        const donutConfig1 = {
            type: 'doughnut',
            data: donutData1,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const myDonutChart1 = new Chart(donutCtx1, donutConfig1);

        function generateLegend1(chart) {
            const legendContainer = document.getElementById('donutLegend1');
            legendContainer.innerHTML = chart.data.labels.map((label, index) => `
                <div>
                    <span style="display:inline-block;width:10px;height:10px;margin-right:5px;background-color:${chart.data.datasets[0].backgroundColor[index]};"></span>
                    ${label}
                </div>
            `).join('');
        }

        generateLegend1(myDonutChart1);
    });

    </script>
@endsection
