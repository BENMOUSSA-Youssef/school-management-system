<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Grades - Student Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dark-theme">
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-full">EMSISystem</div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><span class="nav-icon">🏠</span><span class="nav-text">Dashboard</span></a></li>
                    <li><a href="{{ url('/results') }}" class="{{ request()->is('results') ? 'active' : '' }}"><span class="nav-icon">📈</span><span class="nav-text">Result</span></a></li>
                    <li><a href="{{ route('grades.index') }}" class="{{ request()->is('grades*') ? 'active' : '' }}"><span class="nav-icon">📝</span><span class="nav-text">Grade</span></a></li>
                    <li><a href="{{ route('students.index') }}" class="{{ request()->is('students*') ? 'active' : '' }}"><span class="nav-icon">👥</span><span class="nav-text">Student</span></a></li>
                    <li><a href="{{ route('modules.index') }}" class="{{ request()->is('modules*') ? 'active' : '' }}"><span class="nav-icon">📚</span><span class="nav-text">Module</span></a></li>
                    <li><a href="{{ url('/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}"><span class="nav-icon">⚙️</span><span class="nav-text">Profile</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-role">Admin</div>
                    <div class="sidebar-user-role">Teacher</div>
                </div>
                <button class="sidebar-logout-btn" onclick="logoutUser()">Logout</button>
                <div class="theme-toggle-sidebar" style="margin-top: var(--spacing-md);">
                    <button class="theme-btn active" id="darkThemeBtn" onclick="setTheme('dark')" title="Dark mode">🌙</button>
                    <button class="theme-btn" id="lightThemeBtn" onclick="setTheme('light')" title="Light mode">☀️</button>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-navbar-simple">
                <div class="navbar-logo">EMSISystem</div>
                <h1>Grades Assignment</h1>
                <div class="user-badge" id="userBadge">Teacher</div>
            </div>

            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success" style="display: block; margin-bottom: 1rem;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card" style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; margin-bottom: 1.5rem;">
                    <div style="padding: 1rem;">
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-size: 14px; font-weight: 600;">Instructions for Teachers:</h3>
                        <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-secondary); font-size: 13px; line-height: 1.6;">
                            <li><strong>Enter Grades:</strong> Type a number between 0 and 20 in the 'Grade' field for each student and module.</li>
                            <li><strong>Enter Absences:</strong> Type the number of absences in the 'Absences' field (0 or positive number).</li>
                            <li><strong>Auto-Save:</strong> Data is saved automatically when you click outside the input field or press Enter.</li>
                            <li><strong>Color Coding:</strong> Grades are color-coded (Green ≥16, Blue ≥14, Yellow ≥12, Light Blue ≥10, Red &lt;10).</li>
                        </ul>
                    </div>
                </div>

                @if($students->count() > 0 && $modules->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h2>Grades & Absences Table</h2>
                            <div style="color: var(--text-secondary); font-size: 12px; margin-top: 0.5rem;">
                                <span style="color: #10b981;">✓</span> Displayed: {{ $students->count() }} rows
                            </div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid var(--border-light);">
                                            <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Student Name</th>
                                            <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">CIN</th>
                                            <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Group</th>
                                            @foreach($modules as $module)
                                                <th style="padding: 12px; text-align: center; font-weight: 600; color: var(--text-primary); border-left: 1px solid var(--border-light);">
                                                    <div>{{ $module->name }}</div>
                                                    <div style="font-size: 11px; font-weight: 400; color: var(--text-secondary); margin-top: 4px;">
                                                        Coef: {{ $module->coefficient }} | Exam: {{ $module->exam_date ? $module->exam_date->format('d/m/Y') : 'Not set' }}
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            <tr style="border-bottom: 1px solid var(--border-light);">
                                                <td style="padding: 12px; color: var(--text-primary);">{{ $student->full_name }}</td>
                                                <td style="padding: 12px; color: var(--text-primary);">{{ $student->registration_number }}</td>
                                                <td style="padding: 12px; color: var(--text-primary);">{{ $student->class }}</td>
                                                @foreach($modules as $module)
                                                    @php
                                                        $grade = $student->grades->where('module_id', $module->id)->first();
                                                        $gradeValue = $grade && $grade->score !== null ? $grade->score : '';
                                                        $absenceValue = $grade ? $grade->absences : 0;
                                                        $gradeColor = '';
                                                        if ($gradeValue !== '' && $gradeValue !== null) {
                                                            if ($gradeValue >= 16) $gradeColor = '#10b981';
                                                            elseif ($gradeValue >= 14) $gradeColor = '#3b82f6';
                                                            elseif ($gradeValue >= 12) $gradeColor = '#f59e0b';
                                                            elseif ($gradeValue >= 10) $gradeColor = '#06b6d4';
                                                            else $gradeColor = '#ef4444';
                                                        }
                                                    @endphp
                                                    <td style="padding: 12px; text-align: center; border-left: 1px solid var(--border-light);">
                                                        <div style="display: flex; flex-direction: column; gap: 8px; min-width: 120px;">
                                                            <div>
                                                                <label style="font-size: 11px; color: var(--text-secondary); display: block; margin-bottom: 4px;">Grade (0-20)</label>
                                                                <input 
                                                                    type="number" 
                                                                    name="grades[{{ $student->id }}][{{ $module->id }}][score]" 
                                                                    value="{{ $gradeValue }}" 
                                                                    min="0" 
                                                                    max="20" 
                                                                    step="0.01"
                                                                    class="grade-input"
                                                                    data-student="{{ $student->id }}"
                                                                    data-module="{{ $module->id }}"
                                                                    style="width: 100%; padding: 6px; border: 1px solid var(--border-medium); border-radius: 4px; background: var(--bg-secondary); color: var(--text-primary); {{ $gradeColor ? 'border-color: ' . $gradeColor . '; color: ' . $gradeColor . '; font-weight: 600;' : '' }}"
                                                                    onchange="saveGrade({{ $student->id }}, {{ $module->id }}, this.value, 'score')"
                                                                >
                                                            </div>
                                                            <div>
                                                                <label style="font-size: 11px; color: var(--text-secondary); display: block; margin-bottom: 4px;">Absences</label>
                                                                <input 
                                                                    type="number" 
                                                                    name="grades[{{ $student->id }}][{{ $module->id }}][absences]" 
                                                                    value="{{ $absenceValue }}" 
                                                                    min="0"
                                                                    class="absence-input"
                                                                    data-student="{{ $student->id }}"
                                                                    data-module="{{ $module->id }}"
                                                                    style="width: 100%; padding: 6px; border: 1px solid var(--border-medium); border-radius: 4px; background: var(--bg-secondary); color: var(--text-primary);"
                                                                    onchange="saveGrade({{ $student->id }}, {{ $module->id }}, this.value, 'absences')"
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                            @if($students->count() === 0)
                                <p>No students found. Please add students first.</p>
                            @elseif($modules->count() === 0)
                                <p>No modules found. Please add modules first.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
    <script>
        function saveGrade(studentId, moduleId, value, type) {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('student_id', studentId);
            formData.append('module_id', moduleId);
            formData.append(type, value);
            
            fetch('{{ route("grades.store") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    // Update color based on grade
                    if (type === 'score' && value !== '' && value !== null) {
                        const input = document.querySelector(`input[data-student="${studentId}"][data-module="${moduleId}"][name*="score"]`);
                        const grade = parseFloat(value);
                        let color = '';
                        if (grade >= 16) color = '#10b981';
                        else if (grade >= 14) color = '#3b82f6';
                        else if (grade >= 12) color = '#f59e0b';
                        else if (grade >= 10) color = '#06b6d4';
                        else color = '#ef4444';
                        
                        if (input) {
                            input.style.borderColor = color;
                            input.style.color = color;
                            input.style.fontWeight = '600';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
