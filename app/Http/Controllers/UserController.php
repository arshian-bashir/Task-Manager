<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(Project $project)
    {
        $users = User::with('project')->get();

        return view('users.index', compact('users'));
    }
    public function edit(User $user, Project $project)
    {
        return view('users.edit', compact('user'));
    }
    public function create(Request $request)
    {   
        $projects = Project::all();
        return view('users.create', compact('projects'));
    }
    public function store(Request $request)
    {   
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id', 
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required', 
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->role = '2';
        $user->project_id = $validated['project_id'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        $user->save();

        $users = User::with('project')->get();

        return view('users.index', compact('users'));
    }

    public function employee_index(Request $request)
    { 
        $users = User::all();
        $projects = Project::all();
        return view('users.employee_index', compact('users', 'projects'));
    }
    
    public function getByDepartment($departmentId)
    {
        $users = User::where('project_id', $departmentId)->get();
        return response()->json($users);
    }

}
