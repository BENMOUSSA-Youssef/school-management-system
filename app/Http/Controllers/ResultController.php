<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Module;
use App\Traits\CalculatesAverage;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    use CalculatesAverage;
    
    /**
     * Display results with filtering and sorting
     */
    public function index(Request $request)
    {
        // Get all students with their grades (eager loading)
        $query = Student::with(['grades.module']);
        $modules = Module::all();
        
        // Apply filtering
        $filter = $request->get('filter', 'all');
        $sort = $request->get('sort', 'name');
        
        $students = $query->get()->map(function ($student) use ($modules) {
            // Calculate weighted average using modules
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

            $student->final_average = $totalCoefficient > 0 ? round($weightedAverage / $totalCoefficient, 2) : null;
            $student->status = CalculatesAverage::determineStatus($student->final_average);
            $student->mention = CalculatesAverage::getMention($student->final_average);
            $student->total_absences = $totalAbsences;
            $student->module_grades = $student->grades->keyBy('module_id');
            return $student;
        });
        
        // Apply filter
        if ($filter === 'passed') {
            $students = $students->filter(function ($student) {
                return $student->final_average !== null && $student->final_average >= 10;
            });
        } elseif ($filter === 'failed') {
            $students = $students->filter(function ($student) {
                return $student->final_average !== null && $student->final_average < 10;
            });
        }
        
        // Apply sorting
        if ($sort === 'name') {
            $students = $students->sortBy('full_name')->values();
        } elseif ($sort === 'average-desc') {
            $students = $students->sortByDesc(function ($student) {
                return $student->final_average ?? -1;
            })->values();
        } elseif ($sort === 'average-asc') {
            $students = $students->sortBy(function ($student) {
                return $student->final_average ?? 999;
            })->values();
        }
        
        return view('results', compact('students', 'filter', 'sort', 'modules'));
    }
}
