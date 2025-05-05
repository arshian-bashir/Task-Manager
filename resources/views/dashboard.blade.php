@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')

<style>
    #DonutChart2, #DonutChart3 {
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

    #myDonutChart2, #myDonutChart3 {
        flex: 1 1 200px;
        max-width: 100%;
        height: auto;
        max-height: 230px;

    }
</style>

    <div class="container">
        <h2>Welcome to your Dashboard</h2>
        <p>This is your dashboard where you can manage your tasks, routines, notes, and files.</p>
        
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="card-title fw-bold">Tasks</h2>
                        <table class="table table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>To Do</td>
                                    <td>{{ $tasks->where('status', 'to_do')->count() }}</td>
                                </tr>
                                <tr>
                                    <td>In Progress</td>
                                    <td>{{ $tasks->where('status', 'in_progress')->count() }}</td>
                                </tr>
                                <tr>
                                    <td>Completed</td>
                                    <td>{{ $tasks->where('status', 'completed')->count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="card-title fw-bold">Routines</h2>
                        <table class="table table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Frequency</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Daily</td>
                                    <td>{{ $routines->where('frequency', 'daily')->count() }}</td>
                                </tr>
                                <tr>
                                    <td>Weekly</td>
                                    <td>{{ $routines->where('frequency', 'weekly')->count() }}</td>
                                </tr>
                                <tr>
                                    <td>Monthly</td>
                                    <td>{{ $routines->where('frequency', 'monthly')->count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Files</h5>
                        <p class="card-text flex-grow-1">You have <strong></strong> files.</p>
                        <a href="#" class="btn btn-primary mt-auto">View Files</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tasks Due Today ({{ $dueToday->count()}})</h5>
                        <ul class="list-group flex-grow-1">
                            <table class="table table-bordered align-middle">
                            @foreach($dueToday as $task)
                                <tbody>
                                    <tr>
                                    <td style="width: 62%; vertical-align: middle;">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                                            <div style="flex: 1 1 0%; min-width: 0; word-wrap: break-word; white-space: normal;">
                                                <strong>{{ $task->title }}</strong> - {{ $task->project->name }}
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
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tasks Over Due ({{$overdueTasks->count()}})</h5>
                        <ul class="list-group flex-grow-1">
                            <table class="table table-bordered align-middle">
                            @foreach($overdueTasks as $task)
                                <tbody>
                                    <tr>
                                        <td style="width: 62%; vertical-align: middle;">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                                <div style="flex: 1 1 0%; min-width: 0; word-wrap: break-word; white-space: normal;">
                                                    <strong>{{ $task->title }}</strong> - {{ $task->project->name }}
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
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Generate Reports</h5>
                        <div class="dropdown">
                            <a href="{{ route('reports.index') }}" class="btn btn-primary mt-auto">Reports</a>

                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Upcoming Reminders</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($upcomingReminders as $reminder)
                                <li class="list-group-item d-flex justify-content-between align-items-center {{ $reminder->date->isToday() ? 'bg-warning' : ($reminder->date->isPast() ? 'bg-danger' : 'bg-success') }}">
                                    {{ $reminder->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $reminder->date->format('M d') }} {{ $reminder->time ? $reminder->time->format('H:i') : '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Count of tasks as per Department <br>(To Do)</h5>
                            <div id="DonutChart2">
                                <div class="chart-legend-container">
                                    <div id="donutLegend2" class="chart-legend">

                                    </div>
                                </div>
                                <canvas id="myDonutChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Count of tasks as per Department <br>(In Progress)</h5>
                            <div id="DonutChart3">
                                <div class="chart-legend-container">
                                    <div id="donutLegend3" class="chart-legend">

                                    </div>
                                </div>
                                <canvas id="myDonutChart3"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ========== jsPDF Functionality ==========
        const { jsPDF } = window.jspdf;

        const reportBtn = document.querySelector('.reportView');
        if (reportBtn) {
            reportBtn.addEventListener('click', function () {
                const selectedProject = document.querySelector('input[name="project"]:checked');
                if (selectedProject) {
                    const projectId = selectedProject.value;
                    const projectName = selectedProject.nextElementSibling.innerText;

                    const doc = new jsPDF();
                    doc.setFontSize(20);
                    doc.text(`Report for: ${projectName}`, 20, 30);
                    doc.setFontSize(12);
                    doc.text(`Project ID: ${projectId}`, 20, 50);
                    doc.text("This is a sample report generated using jsPDF.", 20, 65);

                    const pdfBlobUrl = doc.output("bloburl");
                    window.open(pdfBlobUrl, "_blank");
                } else {
                    alert('Please select a project first.');
                }
            });
        }

        // ========== Chart.js Doughnut Chart ==========
        const donutCtx2 = document.getElementById('myDonutChart2');
        if (!donutCtx2) {
            console.error('Canvas element #myDonutChart2 not found');
            return;
        }

        const donutData2 = {
            labels: @json($projectTasksToDo->keys()),
            datasets: [{
                label: ' Count',
                data: @json($projectTasksToDo->values()),
                backgroundColor: ["#42A5F5", "#FFEB3B", "#66BB6A", "#FFA726", "#AB47BC", "#26A69A", "#EF5350"],
                hoverBackgroundColor: ["#3498DB", "#F1C40F", "#4CAF50", "#FB8C00", "#8E24AA", "#00897B", "#E53935"],
                borderWidth: 3
            }]
        };

        const donutConfig2 = {
            type: 'doughnut',
            data: donutData2,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const myDonutChart2 = new Chart(donutCtx2, donutConfig2);

        function generateLegend2(chart) {
            const legendContainer = document.getElementById('donutLegend2');
            if (legendContainer) {
                legendContainer.innerHTML = chart.data.labels.map((label, index) => `
                    <div>
                        <span style="display:inline-block;width:10px;height:10px;margin-right:5px;background-color:${chart.data.datasets[0].backgroundColor[index]};"></span>
                        ${label}
                    </div>
                `).join('');
            }
        }

        generateLegend2(myDonutChart2);

        // ========== Chart.js Doughnut Chart ==========
        const donutCtx3 = document.getElementById('myDonutChart3');
        if (!donutCtx3) {
            console.error('Canvas element #myDonutChart3 not found');
            return;
        }

        const donutData3 = {
            labels: @json($projectTasksInProgress->keys()),
            datasets: [{
                label: ' Count',
                data: @json($projectTasksInProgress->values()),
                backgroundColor: ["#42A5F5", "#FFEB3B", "#66BB6A", "#FFA726", "#AB47BC", "#26A69A", "#EF5350"],
                hoverBackgroundColor: ["#3498DB", "#F1C40F", "#4CAF50", "#FB8C00", "#8E24AA", "#00897B", "#E53935"],
                borderWidth: 3
            }]
        };

        const donutConfig3 = {
            type: 'doughnut',
            data: donutData3,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const myDonutChart3 = new Chart(donutCtx3, donutConfig3);

        function generateLegend3(chart) {
            const legendContainer = document.getElementById('donutLegend3');
            if (legendContainer) {
                legendContainer.innerHTML = chart.data.labels.map((label, index) => `
                    <div>
                        <span style="display:inline-block;width:10px;height:10px;margin-right:5px;background-color:${chart.data.datasets[0].backgroundColor[index]};"></span>
                        ${label}
                    </div>
                `).join('');
            }
        }

        generateLegend3(myDonutChart3);
    });
</script>



@endsection
