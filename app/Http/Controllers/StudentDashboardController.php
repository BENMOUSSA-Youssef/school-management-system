<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Traits\CalculatesAverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    use CalculatesAverage;

    /**
     * Display student's own dashboard with grades and absences
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is a student
        if (!$user->isStudent()) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Students only.');
        }
        
        // Get the student record linked to this user
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'No student record found for your account. Please contact administrator.');
        }

        // Load grades with modules
        $student->load(['grades.module']);
        $modules = Module::all();

        // Calculate weighted average
        $weightedAverage = 0;
        $totalCoefficient = 0;
        $totalAbsences = 0;

        foreach ($modules as $module) {
            $grade = $student->grades->where('module_id', $module->id)->first();
            if ($grade && $grade->score !== null) {
                $weightedAverage += $grade->score * $module->coefficient;
                $totalCoefficient += $module->coefficient;
                $totalAbsences += $grade->absences ?? 0;
            }
        }

        $finalAverage = $totalCoefficient > 0 ? round($weightedAverage / $totalCoefficient, 2) : null;
        $status = CalculatesAverage::determineStatus($finalAverage);
        $mention = CalculatesAverage::getMention($finalAverage);

        return view('student-dashboard', compact('student', 'modules', 'finalAverage', 'status', 'mention', 'totalAbsences'));
    }
}
