<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="auth-page">
    <!-- Left Side - Form -->
    <div class="auth-form-section">
        <div class="auth-logo">
            <div class="auth-logo-icon">E</div>
            <span>EMSI System</span>
        </div>
        
        <div class="auth-form-container">
            <div class="auth-header">
                <p class="subtitle">Welcome Back</p>
                <h1>Sign in to your account<span class="dot">.</span></h1>
                <p class="login-link">Don't have an account? <a href="/register">Create account</a></p>
            </div>
            
            @if ($errors->any())
                <div id="alertMessage" class="alert alert-error" style="display: block; margin-bottom: 1rem;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf
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
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">👁</button>
                    </div>
                    @error('password')
                        <small style="color: var(--danger); display: block; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            
            <div style="text-align: center; padding: var(--spacing-lg); background: rgba(14, 165, 233, 0.1); border-radius: 10px; border: 1px solid rgba(14, 165, 233, 0.2);">
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 12px; margin-bottom: var(--spacing-xs);">
                    <strong style="color: var(--primary);">Demo Account:</strong>
                </p>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 12px;">
                    teacher@emsi.ma / admin123
                </p>
            </div>
        </div>
    </div>
    
    <!-- Right Side - Visual -->
    <div class="auth-visual-section">
        <div class="auth-visual-content">
            <h2>Student Management Made Simple</h2>
            <p>Manage students, grades, and results all in one place</p>
            <ul class="auth-visual-features">
                <li>Track student progress</li>
                <li>Calculate weighted averages</li>
                <li>Generate detailed reports</li>
                <li>Role-based access control</li>
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
</body>
</html>
