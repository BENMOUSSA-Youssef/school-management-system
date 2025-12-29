<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules
     */
    public function index()
    {
        $modules = Module::with('grades')->get();
        return view('modules', compact('modules'));
    }
    
    /**
     * Store a newly created module
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'coefficient' => 'required|numeric|min:1|max:10',
            'exam_date' => 'nullable|date',
        ]);
        
        // Auto-generate code from name if not provided
        if (!isset($data['code']) || empty($data['code'])) {
            $baseCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $data['name']), 0, 8));
            $code = $baseCode;
            $counter = 1;
            // Ensure uniqueness
            while (Module::where('code', $code)->exists()) {
                $code = $baseCode . '-' . $counter;
                $counter++;
            }
            $data['code'] = $code;
        }
        
        // Handle exam_date - set to null if empty
        if (isset($data['exam_date']) && $data['exam_date'] === '') {
            $data['exam_date'] = null;
        }
        
        Module::create($data);
        return redirect()->route('modules.index')->with('success', 'Module added successfully!');
    }

    /**
     * Show the form for editing the specified module
     */
    public function edit(Module $module)
    {
        $modules = Module::with('grades')->get();
        return view('modules', compact('module', 'modules'));
    }

    /**
     * Update the specified module
     */
    public function update(Request $request, Module $module)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'coefficient' => 'required|numeric|min:1|max:10',
            'exam_date' => 'nullable|date',
        ]);
        
        // Handle exam_date - set to null if empty
        if (isset($data['exam_date']) && $data['exam_date'] === '') {
            $data['exam_date'] = null;
        }
        
        $module->update($data);
        return redirect()->route('modules.index')->with('success', 'Module updated successfully!');
    }

    /**
     * Remove the specified module
     */
    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route('modules.index')->with('success', 'Module deleted successfully!');
    }
}
