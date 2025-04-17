<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks()->get()->groupBy('status');
        $users = User::all(); 
       
        return view('tasks.index', compact('project', 'tasks', 'users'));
    }

    public function store(Request $request, Project $project)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:to_do,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ]);
       
        $userIds = implode(',', (array) $request->user_id);
        
        $project->tasks()->create([
            'user_id' => $userIds,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,completed',

        ]);

        $task = Task::findOrFail($request->id); 
        $task->update($request->all()); 


        return redirect()->route('projects.tasks.index', $task->project_id)->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['message' => 'Task status updated successfully.']);
    }

    public function showall(Request $request)
    {   

        $userTasks = auth()->user()->assignedTasks()->with('project')->get();
        $projects = Project::with('tasks')->get();
        
        return view('tasks.showall', compact('userTasks'));
    }

    public function assigned(Request $request)
    {   
        $user = Auth::user(); 
        $tasksList = Task::whereHas('project', function ($query) use ($user) { $query->where('user_id', $user->id); })
            ->get()->groupBy('status')
            ->map(function ($group) { return $group->sortBy('priority'); });
        
        return view('tasks.assigned', compact('tasksList'));
    }
    public function employee_show(Request $request)
    {   
        $user = Auth::user();
        $userId = $user->id;
        $userProjectId = $user->project_id;

        $tasksCount = Task::whereRaw("FIND_IN_SET(?, user_id)", [$userId])->count();
        
        $tasksList = Task::whereRaw("FIND_IN_SET(?, user_id)", [$userId])
            ->where('project_id', $userProjectId)
            ->get()->groupBy('status')->map(function ($group) { return $group->sortBy('priority'); });

        return view('tasks.employee_show', compact('tasksCount','tasksList'));
    }

    public function employee_task_show(Task $task, Request $request)
    {   
        return view('tasks.employee_task_show', compact('task'));
    }

    public function employee_depart_tasks(Request $request)
    {   
        $user = Auth::user();
        $userProjectId = $user->project_id;

        $projectUsers = User::where('project_id', $userProjectId)->get();

        $project = Project::find($userProjectId);

        $tasksList = Task::where('project_id', $userProjectId)->get()->groupBy('status');

        return view('tasks.employee_project_tasks', compact('tasksList','projectUsers','project'));
    }

}