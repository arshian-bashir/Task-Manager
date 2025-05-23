<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Task Manager</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/denning-logo.ico') }}" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .card-header {
            background-color: #495057;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-size: 1.25rem;
            font-weight: 500;
        }

        .card-header img {
            filter: invert(100%) brightness(200%);
            max-height: 50px; /* Ensures the logo is not too big */
        }

        .btn-primary {
            background-color: #495057;
            border-color: #495057;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #343a40;
            border-color: #343a40;
        }

        .form-control {
            border-radius: 0.5rem;
        }

        .form-check-label {
            font-weight: 500;
        }

        .text-danger {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <!-- Header -->
                <div class="card-header text-center p-3 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('assets/img/denning_logo_black.png') }}" class="img-fluid" alt="Task Manager Logo">
                </div>

                <!-- Login Form -->
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="usernamee" class="form-label">Username</label>
                            <input type="text" name="usernamee" id="usernamee" class="form-control" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="passwordd" class="form-label">Password</label>
                            <input type="password" name="passwordd" id="passwordd" class="form-control" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Remember Me</label>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer text-center pt-2">
                   <p>&copy; {{ date('Y') }} Task Manager | Developed by Denning IT Software Department</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
