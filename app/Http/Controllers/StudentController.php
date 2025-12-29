<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students with their calculated averages
     */
    public function index()
    {
        $students = Student::with(['grades', 'user'])->get(); // load students m3a lgrades o l user
        $users = User::all(); // Fetches all users from the database.
        return view('students', compact('students', 'users'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $students = Student::with(['grades', 'user'])->get();
        $users = User::all();
        return view('students', compact('students', 'users'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:students,registration_number',
            'class' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Handle user_id - set to null if empty string
        if (isset($data['user_id']) && $data['user_id'] === '') {
            $data['user_id'] = null;
        }
        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        $students = Student::with(['grades', 'user'])->get();
        $users = User::all();
        return view('students', compact('student', 'students', 'users'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:students,registration_number,' . $student->id,
            'class' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Handle user_id - set to null if empty string
        if (isset($data['user_id']) && $data['user_id'] === '') {
            $data['user_id'] = null;
        }
        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}