<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | Task Manager </title>
    <link rel="shortcut icon" href="{{ asset('assets/img/denning-logo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-color: rgb(241 245 249);
            font-family: "Noto Sans", sans-serif !important; 
        }

        .btn {
            padding: .25rem .5rem !important;
            font-size: .875rem !important;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar .nav-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #495057;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            border-radius: 0.25rem;
        }

        .sidebar .nav-link .bi {
            margin-right: 10px;
        }

        .content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .topnav {
            flex-shrink: 0;
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        .navbar-brand {
            font-weight: bold;
            color: #343a40;
        }

        .navbar-nav .nav-link {
            color: #343a40;
        }

        .navbar-nav .nav-link:hover {
            color: #007bff;
        }

        .card {
            border: none;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        footer {
            background-color: #ffffff;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
            flex-shrink: 0;
        }

        main {
            flex-grow: 1;
        }
    </style>
</head>

<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="mb-4 text-center">
            <a href="{{ route('employee_dashboard') }}">
                <img style=" filter: invert(100%) brightness(200%);"
                    src="{{ asset('assets/img/denning_logo_black.png') }}" class="img-fluid" width="100%"
                    alt="task manager">
            </a>
        </h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('employee_dashboard') }}">
                    <i class="bi bi-house-door"></i> Home
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->is('mail*') ? 'active' : '' }}" href="#">
                    <i class="bi bi-inbox"></i> Inbox
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                    href="{{ route('projects.employee_index') }}">
                    <i class="bi bi-folder"></i> Departments
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->is('tasks*') ? 'active' : '' }}" 
                href="#" id="tasksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-check2-square"></i> Tasks
                </a>
                <ul class="dropdown-menu" aria-labelledby="tasksDropdown">
                    <li><a class="dropdown-item" href="{{ route('tasks.employee_show') }}">Tasks assigned to me</a></li>
                    <li><a class="dropdown-item" href="{{ route('employee_depart_tasks') }}">Tasks assigned to {{ Auth::user()->project->name }}</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('routines*') ? 'active' : '' }}"
                    href="{{ route('routines.employee_index')}}">
                    <i class="bi bi-calendar-check"></i> Routines
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('notes*') ? 'active' : '' }}" href="#">
                    <i class="bi bi-sticky"></i> Notes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reminders*') ? 'active' : '' }}"
                    href="#">
                    <i class="bi bi-bell"></i> Reminders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('files*') ? 'active' : '' }}" href="#">
                    <i class="bi bi-file"></i> Files
                </a>
            </li>
        </ul>
    </div>
    <div class="content d-flex flex-column">
        <header class="topnav mb-4">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand" >
                        <span class="fw-normal" id="currentDateTime"></span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            @yield('content')
        </main>
        <footer class="mt-auto py-3 text-center">
            <div class="container">
                <span class="text-muted">&copy; {{ date('Y') }} Task Manager | Developed by Denning IT Software Department<a target="_blank"> </a> </span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateDateTime() {
            const now = new Date();
            const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            const day = dayNames[now.getDay()];
            const date = now.toLocaleDateString(['en-US'], { day: 'numeric', month: 'long', year: 'numeric' });
            const time = now.toLocaleTimeString();

            document.getElementById('currentDateTime').innerText = `${day} | ${date} | ${time}`;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>

</html>
