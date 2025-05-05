<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {   
        $projects = Project::all();
        $users = User::all();
        return view('reports.index', compact('users', 'projects'));
    }

    public function project_create(Request $request)
    {   
        $projectId = $request->input('project_id');
        $project = Project::find($projectId);
        $tasks = Task::where('project_id', $projectId)->get();

        $users = User::where('project_id', $projectId)->get();
        $usersCount = User::where('project_id', $projectId)->count();

        $defaultStatuses = ['to_do' => 0, 'in_progress' => 0, 'completed' => 0];

        $routines = Routine::where('project_id', $projectId)->get();

        $statusesCount = $tasks->groupBy('status')->map->count();
        $statusesCount = collect($defaultStatuses)->merge($statusesCount)->only(array_keys($defaultStatuses));

        return view('reports.project_show', compact('project', 'tasks', 'users', 'usersCount', 'statusesCount', 'routines'));
    }

    public function employee_create(Request $request)
    {   
        
        $userId = $request->input('user_id');
        $user = User::find($userId);

        $project = $user->project;
        $tasks = Task::whereRaw("FIND_IN_SET(?, user_id)", [$userId])->get();
        $tasksCount = $tasks->count();

        $tasksToDo = $tasks->where('status', 'to_do');
        $tasksInProgress = $tasks->where('status', 'in_progress');
        $tasksCompleted = $tasks->where('status', 'completed');

        $tasksCompletedBeforeDue = $tasks->filter(function ($task) {
            return $task->status === 'completed' && $task->updated_at->lte($task->due_date);
        })->count();

        $completedTasks = $tasks->filter(function ($task) {
            return $task->status === 'completed';
        });
        
        $tasksCompletedAfterDue = $completedTasks->filter(function ($task) {
            return \Carbon\Carbon::parse($task->updated_at)->gt(\Carbon\Carbon::parse($task->due_date));
        })->count();
                
        $latenessPercentage = ($tasksCompletedAfterDue / $tasksCount) * 100 ;

        return view('reports.user_show', compact('user', 'project', 'tasks', 'tasksToDo', 'tasksInProgress', 'tasksCompleted', 'latenessPercentage'));
    }

}
