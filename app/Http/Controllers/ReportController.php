<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
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

    public function create(Request $request)
    {   
        $projectId = $request->input('project_id');
        $project = Project::find($projectId);
        $tasks = Task::where('project_id', $projectId)->get();
        $tasksCount = Task::where('project_id', $projectId)->count();

        $users = User::where('project_id', $projectId)->get();
        $usersCount = User::where('project_id', $projectId)->count();

        $defaultStatuses = ['to_do' => 0, 'in_progress' => 0, 'completed' => 0];

        $statusesCount = $tasks->groupBy('status')->map->count();
        $statusesCount = collect($defaultStatuses)->merge($statusesCount)->only(array_keys($defaultStatuses));

        return view('reports.show', compact('project', 'tasks', 'tasksCount', 'users', 'usersCount', 'statusesCount'));
    }

}
