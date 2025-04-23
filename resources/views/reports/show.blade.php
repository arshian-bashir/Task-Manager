@extends('layouts.app')
@section('title')
    Reports
@endsection
@section('content')

<style>
    .table th:nth-child(1) { 
        width: 8%;
    }
    .table th:nth-child(2) { 
        width: 35%;
    }
    .table th:nth-child(3) {
        width: 35%;
    }
    #DonutChart4 {
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

    #myDonutChart4 {
        flex: 1 1 200px;
        max-width: 100%;
        height: auto;
        max-height: 230px;
    }
</style>

<div class="container">
    <div class="bg-white mb-4 shadow-sm p-3 rounded">
        <h2 class="mb-1"><strong>{{ $project->name }} - Department</strong></h2>
        <br>
        <h5 class="mb-0 text-muted">{{ $project->description }}</h5>
    </div>

    <div class="row">
        <div class="col-md-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Total Tasks</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ $tasksCount }}</strong></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Total Users</h5>
                    <br>
                    <h3 class="card-text flex-grow-1"><strong>{{ $usersCount }}</strong></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white mb-4 shadow-sm p-4 rounded">
        <h3 class="mb-3">Employees</h3>
        
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row mb-4">   
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Count of Tasks</h5>
                    <div id="DonutChart4">
                        <div class="chart-legend-container">
                            <div id="donutLegend4" class="chart-legend">
                            </div>
                        </div>
                        <canvas id="myDonutChart4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const donutCtx4 = document.getElementById('myDonutChart4');

        if (!donutCtx4) {
            console.error('Canvas element not found');
            return;
        }
        
        const donutData4 = {
            labels: ['To do', 'In progress', 'Completed'],
            datasets: [{
                label: ' Count ',
                data: @json($statusesCount->values()),
                backgroundColor: ["#42A5F5", "#FFEB3B", "#66BB6A"],
                hoverBackgroundColor: ["#3498DB", "#F1C40F", "#4CAF50"],
                borderWidth: 3
            }]
        };

        const donutConfig4 = {
            type: 'doughnut',
            data: donutData4,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const myDonutChart4 = new Chart(donutCtx4, donutConfig4);

        function generateLegend4(chart) {
            const legendContainer = document.getElementById('donutLegend4');
            legendContainer.innerHTML = chart.data.labels.map((label, index) => `
                <div>
                    <span style="display:inline-block;width:10px;height:10px;margin-right:5px;background-color:${chart.data.datasets[0].backgroundColor[index]};"></span>
                    ${label}
                </div>
            `).join('');
        }

        generateLegend4(myDonutChart4);
    });

    </script>

@endsection