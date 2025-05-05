<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_of_department' =>  'nullable|integer|exists:users,id',
        ]);

        $data = $request->only(['name', 'description']);
        $data['hod_id'] = $request->input('head_of_department');

        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $teamMembers = $project->users()->get();
        $users = User::all();
        $tasksOverDue = Task::where('project_id', $project->id)
            ->whereDate('due_date', '<', Carbon::today())
            ->where('status', '!=', 'completed')
            ->get();        
        return view('projects.show', compact('project', 'teamMembers', 'users', 'tasksOverDue'));
    }
    public function edit(Project $project)
    {
        $users = User::all();
        return view('projects.edit', compact('project','users'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_of_department' =>  'nullable|integer|exists:users,id',
        ]);

        $data = $request->only(['name', 'description']);
        $data['hod_id'] = $request->input('head_of_department');

        $project->update($data);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);
       
        $project = Project::find($request->project_id);
        $project->teamProjects()->attach($request->user_id);
        return redirect()->back()->with('success', 'User added successfully.');
    }
    public function employee_index()
    {
        $projects = Project::all();

        return view('projects.employee_index', compact('projects'));
    }
    public function employee_show(Request $request, $id)
    {

        $users = User::where('project_id', $id)->get();

        return view('projects.employee_show', compact('users'));
    }
}
