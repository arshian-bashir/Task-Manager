<?php
namespace App\Http\Controllers;

use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Carbon\Carbon;

class RoutineController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $upcomingDailyRoutines = Auth::user()->routines()
            ->with('project')
            ->where('frequency', 'daily')
            ->whereJsonContains('days', strtolower($today->format('l')))
            ->take(2)
            ->get();

        $upcomingWeeklyRoutines = Auth::user()->routines()
            ->with('project')
            ->where('frequency', 'weekly')
            ->whereJsonContains('weeks', $today->weekOfYear)
            ->take(2)
            ->get();

        $upcomingMonthlyRoutines = Auth::user()->routines()
            ->with('project')
            ->where('frequency', 'monthly')
            ->get()
            ->filter(function ($routine) use ($today) {
                $months = json_decode($routine->months, true);
                return is_array($months) && in_array($today->month, $months);
            });

        return view('routines.index', compact('upcomingDailyRoutines', 'upcomingWeeklyRoutines', 'upcomingMonthlyRoutines'));
    }

    public function create()
    {   
        $projects = Project::all();
        return view('routines.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'days' => 'nullable|array',
            'weeks' => 'nullable|array',
            'months' => 'nullable|array',
            'project' => 'required|exists:projects,id',
        ]);

        $routineData = $request->only([
            'title',
            'description',
            'frequency',
            'start_time',
            'end_time'
        ]);
        
        $routineData['project_id'] = $request->project;
        
        if ($request->has('days')) {
            $routineData['days'] = json_encode($request->days);
        }
        if ($request->has('weeks')) {
            $routineData['weeks'] = json_encode($request->weeks);
        }
        if ($request->has('months')) {
            $routineData['months'] = json_encode($request->months);
        }
        
        Auth::user()->routines()->create($routineData);

        return redirect()->route('routines.index')->with('success', 'Routine created successfully.');
    }

    public function edit(Routine $routine)
    {   
        $projects = Project::all();
        return view('routines.edit', compact('routine','projects'));
    }

    public function update(Request $request, Routine $routine)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'days' => 'nullable|array',
            'weeks' => 'nullable|array',
            'months' => 'nullable|array',
            'project' => 'required|exists:projects,id',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);
    
        $routineData = $request->all();
    
        $routineData['project_id'] = $request->project;
    
        if ($request->has('days')) {
            $routineData['days'] = json_encode($request->days);
        }
        if ($request->has('weeks')) {
            $routineData['weeks'] = json_encode($request->weeks);
        }
        if ($request->has('months')) {
            $routineData['months'] = json_encode($request->months);
        }
    
        $routine->update($routineData);
    
        if (auth()->user()->role == 1) 
        {
            return redirect()->route('routines.index')->with('success', 'Routine updated successfully.');
        } 
        else {
            return redirect()->route('routines.employee_show',  $routine->id )->with('success', 'Routine updated successfully.');
        }    
    }

    public function destroy(Routine $routine)
    {
        $routine->delete();
        return redirect()->route('routines.index')->with('success', 'Routine deleted successfully.');
    }

    public function showAll()
    {
        $dailyRoutines = Auth::user()->routines()->where('frequency', 'daily')->get();
        $weeklyRoutines = Auth::user()->routines()->where('frequency', 'weekly')->get();
        $monthlyRoutines = Auth::user()->routines()->where('frequency', 'monthly')->get();

        return view('routines.all', compact('dailyRoutines', 'weeklyRoutines', 'monthlyRoutines'));
    }

    public function showDaily()
    {
        $dailyRoutines = Auth::user()->routines()->where('frequency', 'daily')->get();
        return view('routines.daily', compact('dailyRoutines'));
    }

    public function showWeekly()
    {
        $weeklyRoutines = Auth::user()->routines()->where('frequency', 'weekly')->get();
        return view('routines.weekly', compact('weeklyRoutines'));
    }

    public function showMonthly()
    {
        $monthlyRoutines = Auth::user()->routines()->where('frequency', 'monthly')->get();
        return view('routines.monthly', compact('monthlyRoutines'));
    }
    public function employee_index(Request $request)
    {
        $user = Auth::user();
        $userProjectId = $user->project_id;

        $upcomingDailyRoutines = Routine::where('project_id',$userProjectId)
        ->where('frequency', 'daily')
        ->get();

        $upcomingWeeklyRoutines = Routine::where('project_id',$userProjectId)
        ->where('frequency', 'weekly')
        ->get();

        $upcomingMonthlyRoutines = Routine::where('project_id',$userProjectId)
            ->where('frequency', 'monthly')
            ->get();
        return view('routines.employee_index', compact('upcomingDailyRoutines','upcomingWeeklyRoutines','upcomingMonthlyRoutines'));
    }
    public function employee_show(Request $request, $routine)
    {
        $routine = Routine::find($routine);

        return view('routines.employee_show', compact('routine'));
    }
}
