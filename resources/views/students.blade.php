<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students - Student Management System</title>
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
                    <li><a href="{{ route('students.index') }}" class="{{ request()->is('students*') ? 'active' : '' }}"><span class="nav-icon">👥</span><span class="nav-text">Student</span></a></li>
                    <li><a href="{{ route('modules.index') }}" class="{{ request()->is('modules*') ? 'active' : '' }}"><span class="nav-icon">📚</span><span class="nav-text">Module</span></a></li>
                    <li><a href="{{ url('/grades') }}" class="{{ request()->is('grades') ? 'active' : '' }}"><span class="nav-icon">📝</span><span class="nav-text">Grade</span></a></li>
                    <li><a href="{{ url('/results') }}" class="{{ request()->is('results') ? 'active' : '' }}"><span class="nav-icon">📈</span><span class="nav-text">Result</span></a></li>
                    <li><a href="{{ url('/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}"><span class="nav-icon">⚙️</span><span class="nav-text">Profile</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-role">Admin</div>
                    <div class="sidebar-user-role">Teacher</div>
                </div>
                <button class="sidebar-logout-btn" onclick="logoutUser()">Logout</button>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-navbar-simple">
                <div class="navbar-logo">EMSISystem</div>
                <h1>Student Management</h1>
                <div class="user-badge" id="userBadge">Teacher</div>
            </div>

            <div class="content-area">
                <div class="card">
                    <div class="card-header">
                        <h2 id="formTitle">{{ isset($student) ? 'Edit Student' : 'Add New Student' }}</h2>
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
                    
                    <form id="studentForm" method="POST" action="{{ isset($student) ? route('students.update', $student) : route('students.store') }}">
                        @csrf
                        @if(isset($student))
                            @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="studentName">Full Name *</label>
                            <input type="text" id="studentName" name="full_name" value="{{ old('full_name', $student->full_name ?? '') }}" required placeholder="Enter student name">
                            @error('full_name')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="studentCIN">CIN *</label>
                            <input type="text" id="studentCIN" name="registration_number" value="{{ old('registration_number', $student->registration_number ?? '') }}" required placeholder="Enter CIN">
                            @error('registration_number')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="studentGroup">Group *</label>
                            <input type="text" id="studentGroup" name="class" value="{{ old('class', $student->class ?? '') }}" required placeholder="e.g., 3IIR-1">
                            @error('class')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="studentUserId">Link to User Account (Optional)</label>
                            <select name="user_id" id="studentUserId" class="form-control">
                                <option value="">Select User Account (Optional)</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', isset($student) && $student->user_id ? $student->user_id : '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: var(--text-secondary); display: block; margin-top: 4px;">Link this student to an existing user account for login access</small>
                            @error('user_id')
                                <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <button type="submit" class="btn btn-primary">{{ isset($student) ? 'Update Student' : 'Save Student' }}</button>
                            @if(isset($student))
                                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Students List</h2>
                    </div>
                    @if($students->count() > 0)
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid var(--border-light);">
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Name</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">CIN</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Group</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Linked User</th>
                                        <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $studentItem)
                                        <tr style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $studentItem->full_name }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $studentItem->registration_number }}</td>
                                            <td style="padding: 12px; color: var(--text-primary);">{{ $studentItem->class }}</td>
                                            <td style="padding: 12px; color: var(--text-secondary);">
                                                <small>{{ $studentItem->user ? $studentItem->user->name . ' (' . $studentItem->user->email . ')' : 'Not linked' }}</small>
                                            </td>
                                            <td style="padding: 12px;">
                                                <div style="display: flex; gap: 8px;">
                                                    <a href="{{ route('students.edit', $studentItem) }}" class="btn btn-sm" style="padding: 6px 12px; background: #f59e0b; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;" title="Edit">
                                                        Edit
                                                    </a>
                                                    <button type="button" onclick="confirmDelete({{ $studentItem->id }}, '{{ $studentItem->full_name }}')" class="btn btn-sm" style="padding: 6px 12px; background: var(--danger); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;" title="Delete">
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
                            <p>No students found. Add your first student using the form above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
    <script>
        function confirmDelete(studentId, studentName) {
            if (confirm(`Are you sure you want to delete "${studentName}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/students/${studentId}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
