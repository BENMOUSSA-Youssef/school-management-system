<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modules - Student Management System</title>
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
                <h1>Module Management</h1>
                <div class="user-badge" id="userBadge">Teacher</div>
            </div>

            <div class="content-area">
                <div class="card">
                    <div class="card-header">
                        <h2 id="formTitle">{{ isset($module) ? 'Edit Module' : 'Add New Module' }}</h2>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success" style="display: block; margin-bottom: 1rem;">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-error" style="display: block; margin-bottom: 1rem;">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    
                    <form id="moduleForm" method="POST" action="{{ isset($module) ? route('modules.update', $module) : route('modules.store') }}">
                        @csrf
                        @if(isset($module))
                            @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="moduleName">Module Name *</label>
                            <input type="text" id="moduleName" name="name" value="{{ old('name', $module->name ?? '') }}" required placeholder="e.g., Algorithmique et Programmation">
                            @error('name')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="moduleCoefficient">Coefficient *</label>
                            <input type="number" id="moduleCoefficient" name="coefficient" value="{{ old('coefficient', $module->coefficient ?? '') }}" required min="1" max="10" step="0.5" placeholder="e.g., 3">
                            @error('coefficient')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="moduleExamDate">Exam/Control Date (Optional)</label>
                            <input type="date" id="moduleExamDate" name="exam_date" value="{{ old('exam_date', isset($module) && $module->exam_date ? $module->exam_date->format('Y-m-d') : '') }}" placeholder="mm/dd/yyyy">
                            <small style="color: var(--text-secondary); display: block; margin-top: 4px;">Set the date for exams or controls for this module</small>
                            @error('exam_date')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <button type="submit" class="btn btn-primary">{{ isset($module) ? 'Update Module' : 'Save Module' }}</button>
                            @if(isset($module))
                                <a href="{{ route('modules.index') }}" class="btn btn-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Modules List</h2>
                    </div>
                    @if($modules->count() > 0)
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid var(--border-light);">
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Module Name</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Coefficient</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Exam Date</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $moduleItem)
                                        <tr style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $moduleItem->name }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $moduleItem->coefficient }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">
                                                {{ $moduleItem->exam_date ? $moduleItem->exam_date->format('d/m/Y') : 'Not set' }}
                                            </td>
                                            <td style="padding: 12px;">
                                                <div style="display: flex; gap: 8px;">
                                                    <a href="{{ route('modules.edit', $moduleItem) }}" class="btn btn-sm" style="padding: 6px 12px; background: #f59e0b; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;" title="Edit">
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDelete({{ $moduleItem->id }}, '{{ $moduleItem->name }}')" class="btn btn-sm" style="padding: 6px 12px; background: var(--danger); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;" title="Delete">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                            <p>No modules found. Add your first module using the form above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
    <script>
        function confirmDelete(moduleId, moduleName) {
            if (confirm(`Are you sure you want to delete "${moduleName}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/modules/${moduleId}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
