document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier_id');
    const createBtn = document.getElementById('createContractBtn');
    const performanceBtn = document.getElementById('viewPerformanceBtn');

    supplierSelect.addEventListener('change', function() {
        const selectedId = this.value;
        if (selectedId) {
            createBtn.href = `/supplier/${selectedId}/contracts/create`;
            createBtn.classList.remove('disabled');
            createBtn.setAttribute('aria-disabled', 'false');
	   
	    performanceBtn.href = `/supplier/${selectedId}/performance`;
            performanceBtn.classList.remove('disabled');
            performanceBtn.setAttribute('aria-disabled', 'false');
        } else {
            createBtn.href = '#';
            createBtn.classList.add('disabled');
            createBtn.setAttribute('aria-disabled', 'true');

	    performanceBtn.href = '#';
            performanceBtn.classList.add('disabled');
            performanceBtn.setAttribute('aria-disabled', 'true');
        }
    });

    // Trigger once on page load
    supplierSelect.dispatchEvent(new Event('change'));
});
