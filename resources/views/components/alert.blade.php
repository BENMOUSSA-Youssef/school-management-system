@php
    // If no message provided, try to get from session
    $displayMessage = $message ?? session($type);
    
    // Map type to CSS class
    $alertClass = match($type) {
        'success' => 'alert-success',
        'error' => 'alert-error',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        default => 'alert-info'
    };
@endphp

@if($displayMessage)
    <div class="alert {{ $alertClass }}" style="display: block; margin-bottom: 1rem;">
        {{ $displayMessage }}
    </div>
@endif
