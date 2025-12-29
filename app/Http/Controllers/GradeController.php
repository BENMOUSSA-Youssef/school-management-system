<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Module;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of grades with eager loading
     */
    public function index()
    {
        $grades = Grade::with(['student', 'module'])->get();
        $students = Student::orderBy('full_name')->get();
        $modules = Module::orderBy('name')->get();
        
        // Load grades with modules for proper coefficient calculation
        foreach ($students as $student) {
            $student->load(['grades.module']);
        }
        
        return view('grades', compact('grades', 'students', 'modules'));
    }
    
    /**
     * Store a newly created grade or update existing
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'module_id' => 'required|exists:modules,id',
            'score' => 'nullable|numeric|min:0|max:20',
            'absences' => 'nullable|integer|min:0',
        ]);
        
        // Find existing grade or create new
        $grade = Grade::where('student_id', $data['student_id'])
                      ->where('module_id', $data['module_id'])
                      ->first();
        
        if ($grade) {
            // Update existing grade
            if (isset($data['score']) && $data['score'] !== '') {
                $grade->score = $data['score'];
            }
            if (isset($data['absences'])) {
                $grade->absences = $data['absences'] ?? 0;
            }
            $grade->save();
        } else {
            // Create new grade only if score is provided
            if (isset($data['score']) && $data['score'] !== '') {
                Grade::create([
                    'student_id' => $data['student_id'],
                    'module_id' => $data['module_id'],
                    'score' => $data['score'],
                    'absences' => $data['absences'] ?? 0,
                ]);
            }
        }
        
        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'Grade saved successfully!']);
        }
        
        return redirect()->route('grades.index')->with('success', 'Grade assigned successfully!');
    }

    /**
     * Show the form for editing the specified grade
     */
    public function edit(Grade $grade)
    {
        $grades = Grade::with(['student', 'module'])->latest()->get();
        $students = Student::all();
        $modules = Module::all();
        return view('grades', compact('grade', 'grades', 'students', 'modules'));
    }

    /**
     * Update the specified grade
     */
    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'module_id' => 'required|exists:modules,id',
            'score' => 'required|numeric|min:0|max:20',
        ]);
        
        $grade->update($data);
        return redirect()->route('grades.index')->with('success', 'Grade updated successfully!');
    }

    /**
     * Remove the specified grade
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully!');
    }
}
