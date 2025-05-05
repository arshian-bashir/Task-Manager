<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'checkRole:1'])->group(function () {

    Route::get('/', [LoginController::class, 'dashboard'])->name('dashboard');
 
    Route::controller(MailController::class)->prefix('mail')->name('mail.')->group(function () {
        Route::get('/', 'index')->name('inbox');    
    });

    Route::resource('projects', ProjectController::class);
    Route::post('project/team', [ProjectController::class, 'addMember'])->name('projects.addMember');
    
    Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

    Route::get('tasks', [TaskController::class, 'showall'])->name('tasks.showall');

    Route::get('tasks/assigned', [TaskController::class, 'assigned'])->name('tasks.assigned');
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    Route::get('/users-by-department/{department}', [UserController::class, 'getByDepartment']);

    Route::post('/projects/tasks/{task}/update-status', [TaskController::class, 'updateStatus']);
    Route::post('/comment_update', [TaskController::class, 'commment_update'])->name('tasks.commment_update');
    
    Route::resource('routines', RoutineController::class)->except(['show']);
    Route::get('routines/showAll', [RoutineController::class, 'showAll'])->name('routines.showAll');
    Route::get('routines/daily', [RoutineController::class, 'showDaily'])->name('routines.showDaily');
    Route::get('routines/weekly', [RoutineController::class, 'showWeekly'])->name('routines.showWeekly');
    Route::get('routines/monthly', [RoutineController::class, 'showMonthly'])->name('routines.showMonthly');
    Route::resource('files', FileController::class);
    Route::resource('notes', NoteController::class);
    Route::resource('reminders', ReminderController::class);
    Route::resource('checklist-items', ChecklistItemController::class);
    Route::get('checklist-items/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('checklist-items.update-status');

    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users', [UserController::class, 'store'])->name('users.store');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post ('reports/project', [ReportController::class, 'project_create'])->name('reports.project_create');
    Route::post('reports/employee', [ReportController::class, 'employee_create'])->name('reports.employee_create');

    Route::post('reports/employee', [ReportController::class, 'employee_create'])->name('reports.employee_create');

    // Route::get('reports/show', [ReportController::class, 'show'])->name('reports.show');
});

Route::middleware(['auth', 'checkRole:2'])->group(function () {

    Route::get('/emp-dash', [LoginController::class, 'employee_dashboard'])->name('employee_dashboard');

    Route::get('/emp-depart', [ProjectController::class, 'employee_index'])->name('projects.employee_index');
    Route::get('/emp-depart/{id}', [ProjectController::class, 'employee_show'])->name('projects.employee_show');
    Route::get('/emp-tasks', [TaskController::class, 'employee_show'])->name('tasks.employee_show');
    Route::get('/emp-depart-tasks', [TaskController::class, 'employee_depart_tasks'])->name('employee_depart_tasks');
    Route::get('/emp-tasks/{task}', [TaskController::class, 'employee_task_show'])->name('tasks.employee_task_show');

    Route::get('/emp-routines', [RoutineController::class, 'employee_index'])->name('routines.employee_index');
    Route::get('emp-routines/{routine}', [RoutineController::class, 'employee_show'])->name('routines.employee_show');
    Route::put('emp-routines/{routine}', [RoutineController::class, 'update'])->name('employee_routines.update');

    Route::put('emp-tasks/{task}/update', [TaskController::class, 'update'])->name('tasks.employee_update');
    Route::post('emp-tasks-create/{project}', [TaskController::class, 'store'])->name('tasks.employee_task_create');
    
    Route::post('/emp-comment_update', [TaskController::class, 'employee_commment_update'])->name('tasks.employee_commment_update');

    Route::get('/emp-users', [UserController::class, 'employee_index'])->name('users.employee_index');

});