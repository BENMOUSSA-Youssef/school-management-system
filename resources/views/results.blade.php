<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Results - Student Management System</title>
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
                    <li><a href="{{ route('results.index') }}" class="{{ request()->is('results*') ? 'active' : '' }}"><span class="nav-icon">📈</span><span class="nav-text">Result</span></a></li>
                    <li><a href="{{ url('/grades') }}" class="{{ request()->is('grades') ? 'active' : '' }}"><span class="nav-icon">📝</span><span class="nav-text">Grade</span></a></li>
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
                <h1>Results & Statistics</h1>
                <div class="user-badge" id="userBadge">Teacher</div>
            </div>

            <div class="content-area">
                <div class="filter-bar" style="display: flex; gap: 2rem; align-items: center; margin-bottom: 1.5rem; padding: 1rem; background: var(--bg-secondary); border-radius: 8px;">
                    <form method="GET" action="{{ route('results.index') }}" id="filterForm" style="display: flex; gap: 2rem; align-items: center; width: 100%;">
                        <div class="filter-group" style="display: flex; align-items: center; gap: 0.5rem;">
                            <label for="filter" style="color: var(--text-primary); font-weight: 500;">Filter:</label>
                            <select name="filter" id="filter" onchange="document.getElementById('filterForm').submit();" style="padding: 6px 12px; border: 1px solid var(--border-medium); border-radius: 4px; background: var(--bg-primary); color: var(--text-primary);">
                                <option value="all" {{ ($filter ?? 'all') == 'all' ? 'selected' : '' }}>All Students</option>
                                <option value="passed" {{ ($filter ?? '') == 'passed' ? 'selected' : '' }}>Passed Only (≥10)</option>
                                <option value="failed" {{ ($filter ?? '') == 'failed' ? 'selected' : '' }}>Failed Only (&lt;10)</option>
                            </select>
                        </div>
                        <div class="filter-group" style="display: flex; align-items: center; gap: 0.5rem;">
                            <label for="sort" style="color: var(--text-primary); font-weight: 500;">Sort by:</label>
                            <select name="sort" id="sort" onchange="document.getElementById('filterForm').submit();" style="padding: 6px 12px; border: 1px solid var(--border-medium); border-radius: 4px; background: var(--bg-primary); color: var(--text-primary);">
                                <option value="name" {{ ($sort ?? 'name') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="average-desc" {{ ($sort ?? '') == 'average-desc' ? 'selected' : '' }}>Average (High to Low)</option>
                                <option value="average-asc" {{ ($sort ?? '') == 'average-asc' ? 'selected' : '' }}>Average (Low to High)</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="results-section">
                    <h2 class="section-title" style="margin-bottom: 1rem; color: var(--text-primary);">Student Results</h2>
                    @if($students->count() > 0)
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid var(--border-light);">
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">NAME</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">CIN</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">GROUP</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">AVERAGE</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">STATUS</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">MENTION</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">TOTAL ABSENCES</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">GRADES & DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        @php
                                            $isPass = $student->final_average !== null && $student->final_average >= 10;
                                            $isNA = $student->final_average === null;
                                            $averageColor = '';
                                            if (!$isNA) {
                                                if ($student->final_average >= 16) $averageColor = '#10b981';
                                                elseif ($student->final_average >= 14) $averageColor = '#3b82f6';
                                                elseif ($student->final_average >= 12) $averageColor = '#f59e0b';
                                                elseif ($student->final_average >= 10) $averageColor = '#06b6d4';
                                                else $averageColor = '#ef4444';
                                            }
                                            
                                            $mentionColor = '';
                                            if ($student->mention === 'Très Bien' || $student->mention === 'Bien') $mentionColor = '#10b981';
                                            elseif ($student->mention === 'Assez Bien') $mentionColor = '#3b82f6';
                                            elseif ($student->mention === 'Passable') $mentionColor = '#f59e0b';
                                            elseif ($student->mention === 'Ajourné') $mentionColor = '#ef4444';
                                            else $mentionColor = '#6b7280';
                                            
                                            $absenceColor = $student->total_absences > 10 ? '#ef4444' : ($student->total_absences > 5 ? '#f59e0b' : '#10b981');
                                        @endphp
                                        <tr style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 12px; color: var(--text-primary); font-weight: 600;">{{ $student->full_name }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $student->registration_number }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $student->class }}</td>
                                            <td style="padding: 12px; color: {{ $averageColor ?: 'var(--text-primary)' }}; font-weight: 700;">
                                                {{ $student->final_average !== null ? number_format($student->final_average, 2) . '/20' : 'N/A' }}
                                            </td>
                                            <td style="padding: 12px;">
                                                <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; {{ $isPass ? 'background-color: #10b981; color: white;' : ($isNA ? 'background-color: #6b7280; color: white;' : 'background-color: #ef4444; color: white;') }}">
                                                    {{ $isPass ? 'PASSED' : ($isNA ? 'N/A' : 'FAILED') }}
                                                </span>
                                            </td>
                                            <td style="padding: 12px;">
                                                <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background-color: {{ $mentionColor }}; color: white;">
                                                    {{ $student->mention }}
                                                </span>
                                            </td>
                                            <td style="padding: 12px; color: {{ $absenceColor }}; font-weight: 600;">
                                                {{ $student->total_absences }}
                                            </td>
                                            <td style="padding: 12px;">
                                                <div style="text-align: left; font-size: 11px; max-width: 300px;">
                                                    @foreach($student->grades as $grade)
                                                        @php
                                                            $gradeColor = '';
                                                            if ($grade->score !== null) {
                                                                if ($grade->score >= 16) $gradeColor = '#10b981';
                                                                elseif ($grade->score >= 14) $gradeColor = '#3b82f6';
                                                                elseif ($grade->score >= 12) $gradeColor = '#f59e0b';
                                                                elseif ($grade->score >= 10) $gradeColor = '#06b6d4';
                                                                else $gradeColor = '#ef4444';
                                                            }
                                                            $examDate = $grade->module->exam_date ? $grade->module->exam_date->format('d M') : 'No date';
                                                        @endphp
                                                        <div style="margin-bottom: 6px;">
                                                            <strong style="color: {{ $gradeColor ?: 'var(--text-secondary)' }};">
                                                                {{ $grade->module->name }}: {{ $grade->score !== null ? number_format($grade->score, 2) . '/20' : 'No grade' }}
                                                            </strong><br>
                                                            <span style="color: var(--text-secondary);">
                                                                Abs: {{ $grade->absences }} | Exam: {{ $examDate }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                    @if($student->grades->isEmpty())
                                                        <span style="color: var(--text-secondary);">No grades</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                            <p>No students found. Add students and grades to see results.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
</body>
</html>
