@props(['target' => 'table-body', 'placeholder' => 'Search...'])

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text">
            <i class="fas fa-search"></i>
        </span>
        <input type="text" class="form-control" id="live-search" placeholder="{{ $placeholder }}">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search');
    const targetElement = document.getElementById('{{ $target }}');
    
    if (searchInput && targetElement) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = targetElement.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script> 