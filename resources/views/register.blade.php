<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="auth-page">
    <!-- Left Side - Form -->
    <div class="auth-form-section">
        <div class="auth-logo">
            <div class="auth-logo-icon">E</div>
            <span>EMSISystem</span>
        </div>
        
        <div class="auth-form-container">
            <div class="auth-header">
                <p class="subtitle">Start For Free</p>
                <h1>Create new account<span class="dot">.</span></h1>
                <p class="login-link">Already a member? <a href="/login">Log in</a></p>
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
            
            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="First name">
                        </div>
                        @error('first_name')
                            <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Last name">
                        </div>
                        @error('last_name')
                            <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                    </div>
                    @error('email')
                        <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" required placeholder="At least 6 characters" minlength="6">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">👁</button>
                    </div>
                    @error('password')
                        <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">👁</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="role">Account Type</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👥</span>
                        <select id="role" name="role" required style="width: 100%; padding: 12px; border: 1px solid var(--border-medium); border-radius: 8px; background: var(--bg-primary); color: var(--text-primary); font-size: 14px;">
                            <option value="">Select account type</option>
                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>
                    <small style="color: var(--text-secondary); display: block; margin-top: 4px;">Teachers can manage students and grades. Students can view their results.</small>
                    @error('role')
                        <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>
        </div>
    </div>
    
    <!-- Right Side - Visual -->
    <div class="auth-visual-section">
        <div class="auth-visual-content">
            <h2>Join Thousands of Users</h2>
            <p>Start managing your academic data efficiently today</p>
            <ul class="auth-visual-features">
                <li>Secure authentication system</li>
                <li>Real-time grade calculations</li>
                <li>Comprehensive student tracking</li>
                <li>Professional dashboard interface</li>
            </ul>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁';
            }
        }
    </script>
