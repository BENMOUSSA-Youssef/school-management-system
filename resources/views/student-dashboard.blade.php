<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Grades - Student Management System</title>
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
                    <li><a href="{{ route('student.dashboard') }}" class="{{ request()->is('student*') ? 'active' : '' }}"><span class="nav-icon">🏠</span><span class="nav-text">My Dashboard</span></a></li>
                    <li><a href="{{ url('/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}"><span class="nav-icon">⚙️</span><span class="nav-text">Profile</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-role">Student</div>
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
                <h1>My Grades & Results</h1>
                <div class="user-badge" id="userBadge">Student</div>
            </div>

            <div class="content-area">
                <!-- Student Info Card -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h2>Student Information</h2>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; padding: 1rem;">
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">Full Name</label>
                            <p style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ $student->full_name }}</p>
                        </div>
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">CIN</label>
                            <p style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ $student->registration_number }}</p>
                        </div>
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">Group</label>
                            <p style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ $student->class }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h2>Academic Summary</h2>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; padding: 1rem;">
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">Average</label>
                            <p style="font-size: 24px; font-weight: 700; color: {{ $finalAverage !== null && $finalAverage >= 10 ? '#10b981' : '#ef4444' }};">
                                {{ $finalAverage !== null ? number_format($finalAverage, 2) : 'N/A' }}/20
                            </p>
                        </div>
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">Status</label>
                            <p style="font-size: 18px; font-weight: 600; color: {{ $status === 'Pass' ? '#10b981' : '#ef4444' }};">
                                {{ $status }}
                            </p>
                        </div>
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">Mention</label>
                            <p style="font-size: 18px; font-weight: 600; color: var(--text-primary);">
                                {{ $mention }}
                            </p>
                        </div>
                        <div>
                            <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase;">Total Absences</label>
                            <p style="font-size: 18px; font-weight: 600; color: {{ $totalAbsences > 10 ? '#ef4444' : ($totalAbsences > 5 ? '#f59e0b' : '#10b981') }};">
                                {{ $totalAbsences }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Grades Table -->
                <div class="card">
                    <div class="card-header">
                        <h2>Grades by Module</h2>
                    </div>
                    @if($modules->count() > 0)
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid var(--border-light);">
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Module Name</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Coefficient</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Grade</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Absences</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Exam Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                        @php
                                            $grade = $student->grades->where('module_id', $module->id)->first();
                                            $score = $grade ? $grade->score : null;
                                            $absences = $grade ? ($grade->absences ?? 0) : 0;
                                            
                                            // Color coding for grades
                                            $gradeColor = '#ef4444'; // red default
                                            if ($score !== null) {
                                                if ($score >= 16) $gradeColor = '#10b981'; // green
                                                elseif ($score >= 14) $gradeColor = '#3b82f6'; // blue
                                                elseif ($score >= 12) $gradeColor = '#f59e0b'; // yellow
                                                elseif ($score >= 10) $gradeColor = '#60a5fa'; // light blue
                                            }
                                        @endphp
                                        <tr style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $module->name }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $module->coefficient }}</td>
                                            <td style="padding: 12px;">
                                                @if($score !== null)
                                                    <span style="font-weight: 600; color: {{ $gradeColor }};">{{ number_format($score, 2) }}/20</span>
                                                @else
                                                    <span style="color: var(--text-secondary);">Not graded</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px;">
                                                <span style="color: {{ $absences > 10 ? '#ef4444' : ($absences > 5 ? '#f59e0b' : '#10b981') }}; font-weight: 600;">
                                                    {{ $absences }}
                                                </span>
                                            </td>
                                            <td style="padding: 12px; color: var(--text-primary);">
                                                {{ $module->exam_date ? $module->exam_date->format('d/m/Y') : 'Not set' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                            <p>No modules found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
</body>
</html>

