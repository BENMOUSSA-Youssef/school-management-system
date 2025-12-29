<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - Student Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
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
                <h1>My Profile</h1>
                <div class="user-badge" id="userBadge">User</div>
            </div>

            <div class="content-area">
                <div class="card">
                    <div class="card-header">
                        <h2>Account Information</h2>
                    </div>
                    @auth
                        <div style="display: grid; gap: var(--spacing-lg);">
                            <div>
                                <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Name</label>
                                <p style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Email</label>
                                <p style="font-size: 16px; font-weight: 600; color: var(--text-primary);">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <label style="display: block; color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Role</label>
                                <p style="font-size: 16px; font-weight: 600; color: var(--text-primary);">
                                    <span class="badge badge-info">User</span>
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="empty-state">Please log in to view your profile.</p>
                    @endauth
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
    <script>
        // Profile page uses Laravel Auth to get user data
    </script>
</body>
</html>
