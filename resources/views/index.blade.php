<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Student Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="dark-theme">
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-full">EMSI System</div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><span class="nav-icon">🏠</span><span class="nav-text">Dashboard</span></a></li>
                    <li><a href="{{ url('/results') }}" class="{{ request()->is('results') ? 'active' : '' }}"><span class="nav-icon">📈</span><span class="nav-text">Result</span></a></li>
                    <li><a href="{{ url('/grades') }}" id="navGrades" class="{{ request()->is('grades') ? 'active' : '' }}"><span class="nav-icon">📝</span><span class="nav-text">Grade</span></a></li>
                    <li><a href="{{ route('students.index') }}" id="navStudents" class="{{ request()->is('students*') ? 'active' : '' }}"><span class="nav-icon">👥</span><span class="nav-text">Student</span></a></li>
                    <li><a href="{{ route('modules.index') }}" id="navModules" class="{{ request()->is('modules*') ? 'active' : '' }}"><span class="nav-icon">📚</span><span class="nav-text">Module</span></a></li>
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
            <div class="top-navbar">
                <div class="navbar-left">
                    <div class="class-info" id="classInfo">3IIR-4</div>
                    <div class="class-members" id="classMembers">
                        <div class="member-avatars"></div>
                        <span class="member-count" id="memberCount">0+</span>
                    </div>
                </div>
                <div class="navbar-center">
                    <div class="search-bar">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Find students, modules, grades..." id="searchInput">
                    </div>
                </div>
                <div class="navbar-right">
                    <button class="notification-btn" title="Notifications">
                        🔔
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </button>
                    <div class="user-profile-dropdown">
                        <div class="user-avatar" id="userAvatar">👤</div>
                        <div class="user-info-dropdown">
                            <div class="user-name" id="userNameDropdown">Loading...</div>
                            <div class="user-role" id="userRoleDropdown">User</div>
                        </div>
                        <span class="dropdown-arrow">▼</span>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <!-- Welcome Section & Calendar -->
                <div class="welcome-section">
                    <div class="welcome-card">
                        <div class="welcome-content">
                            <div class="welcome-greeting">
                                <span>Hi, <span id="welcomeName">User</span></span> 👋
                            </div>
                            <h1 class="welcome-title">Get started with EMSI System</h1>
                            <p class="welcome-subtitle">The best educational platform for managing students and grades</p>
                            <button class="welcome-btn" onclick="window.location.href='/students'">Start Now</button>
                        </div>
                        <div class="welcome-illustration">
                            <div class="illustration-placeholder">🎓</div>
                        </div>
                    </div>
                    <div class="calendar-card">
                        <div class="calendar-header-nav">
                            <button class="calendar-nav-btn" onclick="changeMonth(-1)">‹</button>
                            <h3 id="calendarMonth">April 2024</h3>
                            <button class="calendar-nav-btn" onclick="changeMonth(1)">›</button>
                        </div>
                        <div id="calendarWidget" class="calendar-widget"></div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="statistics-section">
                    <div class="statistics-left">
                        <div class="section-header">
                            <h2 id="statisticsTitle">My Statistics</h2>
                            <a href="/results" class="show-more-link" id="showMoreLink">Show More →</a>
                        </div>

                        <!-- Assessment Completion -->
                        <div class="card">
                            <div class="card-subtitle" id="assessmentTitle">Assessment Completion</div>
                            <select class="period-select" id="assessmentPeriod" onchange="updateAssessmentChart()">
                                <option value="month">This month</option>
                                <option value="week">This week</option>
                                <option value="all">All time</option>
                            </select>
                            <div id="assessmentChart" class="assessment-chart"></div>
                        </div>

                        <!-- Circular Progress Cards -->
                        <div class="progress-cards">
                            <div class="progress-card">
                                <div class="circular-progress" id="examReadiness">
                                    <svg class="progress-ring" width="120" height="120">
                                        <circle class="progress-ring-circle-bg" cx="60" cy="60" r="50"></circle>
                                        <circle class="progress-ring-circle" cx="60" cy="60" r="50"></circle>
                                    </svg>
                                    <div class="progress-text">
                                        <span class="progress-value" id="examValue">0%</span>
                                        <span class="progress-label" id="examLabel">Exam Readiness</span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-card">
                                <div class="circular-progress" id="attendanceProgress">
                                    <svg class="progress-ring" width="120" height="120">
                                        <circle class="progress-ring-circle-bg" cx="60" cy="60" r="50"></circle>
                                        <circle class="progress-ring-circle" cx="60" cy="60" r="50"></circle>
                                    </svg>
                                    <div class="progress-text">
                                        <span class="progress-value" id="attendanceValue">0%</span>
                                        <span class="progress-label">Attendance</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="statistics-right">
                        <div class="section-header">
                            <h2>Recent Activity</h2>
                            <a href="/results" id="viewAllLink" class="show-more-link">View All →</a>
                        </div>
                        <div id="recentActivityList" class="activity-list-modern"></div>
                    </div>
                </div>
                
                <!-- Student Dashboard Additional Charts (only for students) -->
                <div id="studentChartsSection" style="display: none; margin-top: var(--spacing-xl);">
                    <div class="dashboard-grid">
                        <div class="dashboard-left">
                            <div class="card">
                                <div class="card-header">
                                    <h2>My Grades by Subject</h2>
                                </div>
                                <div id="myGradesChart" class="chart-container"></div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h2>Performance Radar</h2>
                                </div>
                                <div id="radarChart" class="chart-container"></div>
                            </div>
                        </div>
                        <div class="dashboard-right">
                            <div class="card">
                                <div class="card-header">
                                    <h2>My Performance</h2>
                                </div>
                                <div id="myPerformanceMetrics" class="metrics-container"></div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h2>My Ranking</h2>
                                </div>
                                <div id="myRanking" class="ranking-container"></div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h2>Recent Grades</h2>
                                </div>
                                <div id="recentGrades" class="recent-grades-list"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}"></script>
    <script>
        // Simple calendar display (no data management, just UI)
        function displaySimpleCalendar() {
            const container = document.getElementById('calendarWidget');
            if (!container) return;
            
            const now = new Date();
            const month = now.getMonth();
            const year = now.getFullYear();
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                               'July', 'August', 'September', 'October', 'November', 'December'];
            const dayNames = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
            
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = now.getDate();
            
            const monthEl = document.getElementById('calendarMonth');
            if (monthEl) {
                monthEl.textContent = `${monthNames[month]} ${year}`;
            }
            
            let html = `
                <div class="calendar">
                    <div class="calendar-weekdays">
                        ${dayNames.map(day => `<div class="calendar-weekday">${day}</div>`).join('')}
                    </div>
                    <div class="calendar-days">
            `;
            
            for (let i = 0; i < firstDay; i++) {
                html += '<div class="calendar-day empty"></div>';
            }
            
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = day === today;
                html += `<div class="calendar-day ${isToday ? 'today' : ''}">${day}</div>`;
            }
            
            html += `
                    </div>
                </div>
            `;
            
            container.innerHTML = html;
        }
        
        // Initialize calendar on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', displaySimpleCalendar);
        } else {
            displaySimpleCalendar();
        }
    </script>
</body>
</html>
