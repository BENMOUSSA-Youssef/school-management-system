@props(['id', 'name', 'route', 'itemType' => 'item'])

<button 
    type="button" 
    onclick="confirmDelete({{ $id }}, '{{ $name }}', '{{ $route }}')" 
    class="btn btn-sm" 
    style="padding: 4px 8px; background: var(--danger); color: white; border: none; border-radius: 4px; cursor: pointer;" 
    title="Delete">
    <i class="fas fa-trash"></i>
</button>

<script>
    function confirmDelete(id, name, route) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/${route}/${id}`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
