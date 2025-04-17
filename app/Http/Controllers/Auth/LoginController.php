<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Project;
use App\Models\Routine;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = Auth::user();
        
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember'); // Check if remember me is checked

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // Regenerate session to prevent session fixation
    
            // Get the authenticated user
            $user = Auth::user();

            
            

            // Check the user's role
            if ($user->role === 1) {
                return redirect()->intended(route('dashboard')); // Admin dashboard
            } elseif ( $user->role === 2) {
                return redirect()->intended(route('employee_dashboard')); // Employee dashboard
            }
    
            // If no matching role, log the user out
            Auth::logout(); 
    
            return redirect()->route('login')->withErrors(['Unauthorized role.']);
        }
    


        return back()->withErrors([
            'email' => __('Invalid email or password. Please try again.'), // More user-friendly error
        ])->withInput($request->only('email')); // Keep email field filled
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session and regenerate CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'You have been logged out.');
    }

    public function dashboard(Request $request)
    {
      
        $user = Auth::user();
        $tasksCount = $user->tasks()->count();
        $routinesCount = $user->routines()->count();
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();
        $recentTasks = $user->tasks()->latest()->take(5)->get();
        $todayRoutines = $user->routines()->whereDate('start_time', now())->get();
        $recentNotes = $user->notes()->latest()->take(5)->get();

        $dueToday = Task::where('due_date', Carbon::today())->get();
        $overdueTasks = Task::where('due_date', '<', Carbon::today())->get();

        $upcomingReminders = $user->reminders()->where('date', '>=', now())->orderBy('date')->take(5)->get();

        return view('dashboard', compact(
            'tasksCount', 
            'routinesCount', 
            'notesCount', 
            'remindersCount',
            'filesCount', 
            'recentTasks',
            'dueToday',
            'overdueTasks', 
            'todayRoutines', 
            'recentNotes', 
            'upcomingReminders'
        ));
    }
        public function employee_dashboard(Request $request)
    {
        $todayName = strtolower(now()->format('l'));
        $todayMonth = now()->format('n'); 

        $user = Auth::user();
        $userId = $user->id;
        $userProjectId = $user->project_id;
        $userProject = Project::find($userProjectId)->name;

        $tasksCount = Task::where('project_id', $userProjectId)
        ->whereRaw("FIND_IN_SET(?, user_id)", [$userId])
        ->count();        
        
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();
        
        $allTasks = Task::whereRaw("FIND_IN_SET(?, user_id)", [$userId])->where('project_id', $userProjectId)->get();

        $dueToady = Task::whereRaw("FIND_IN_SET(?, user_id)", [$userId])
            ->where('project_id', $userProjectId)
            ->where('due_date', Carbon::today())
            ->get();
        
        $overdueTasks = Task::whereRaw("FIND_IN_SET(?, user_id)", [$userId])
            ->where('project_id', $userProjectId)
            ->whereDate('due_date', '<', Carbon::today())
            ->get();

        $todayRoutines = Routine::where('project_id', $userProjectId)->where('frequency','daily')
            ->whereJsonContains('days', $todayName)
            ->get(); 

        $monthRoutines = Routine::where('project_id', $userProjectId)->where('frequency','monthly')
            ->whereJsonContains('months', $todayMonth)
            ->get(); 
                
        $dailyRoutinesCount = $todayRoutines->count(); 
        $monthlyRoutinesCount = $monthRoutines->count(); 
           
        $recentNotes = $user->notes()->latest()->take(5)->get();

        $upcomingReminders = $user->reminders()->where('date', '>=', now())->orderBy('date')->take(5)->get();

        $tasks = Task::where('project_id', $userProjectId)
        ->whereRaw("FIND_IN_SET(?, user_id)", [$userId])->get();
        
        $defaultStatuses = ['to_do' => 0, 'in_progress' => 0, 'completed' => 0];

        $statusesCount = $tasks->groupBy('status')->map->count();
        $statusesCount = collect($defaultStatuses)->merge($statusesCount)->only(array_keys($defaultStatuses));

        $prioritiesCount = $tasks->pluck('priority')->countBy();


        return view('employee_dashboard', compact(
            'userProject',
            'tasksCount', 
            'dailyRoutinesCount',
            'monthlyRoutinesCount',
            'notesCount', 
            'remindersCount',
            'filesCount', 
            'allTasks',
            'dueToady',
            'overdueTasks',
            'todayRoutines', 
            'monthRoutines',
            'recentNotes', 
            'upcomingReminders',
            'statusesCount',
        ));
    }

}
