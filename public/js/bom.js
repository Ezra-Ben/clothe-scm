document.addEventListener('DOMContentLoaded', function () {
    let bomItemIndex = window.initialBomCount || 1;
    const rawMaterials = window.rawMaterials || [];

    const bomItemsContainer = document.getElementById('bom-items-container');
    const addBomItemButton = document.getElementById('add-bom-item');

    addBomItemButton.addEventListener('click', function () {
        const newRow = document.createElement('div');

        const options = rawMaterials.map(rm => 
            `<option value="${rm.id}">${rm.name} (${rm.unit})</option>`
        ).join('');

        newRow.innerHTML = `
            <div class="row mb-3 align-items-end bom-item-row border p-2 rounded bg-light">
                <div class="col-md-5">
                    <label for="bom_items_${bomItemIndex}_raw_material_id" class="form-label">Raw Material</label>
                    <select name="bom_items[${bomItemIndex}][raw_material_id]" id="bom_items_${bomItemIndex}_raw_material_id" class="form-select">
                        <option value="">Select Raw Material</option>
                        ${options}
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="bom_items_${bomItemIndex}_quantity" class="form-label">Quantity</label>
                    <input type="number" name="bom_items[${bomItemIndex}][quantity]" class="form-control" step="0.01" min="0.01">
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-danger remove-bom-item"><i class="fas fa-minus-circle"></i> Remove</button>
                </div>
            </div>
        `;

        bomItemsContainer.appendChild(newRow);
        bomItemIndex++;
    });

    bomItemsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-bom-item') || e.target.closest('.remove-bom-item')) {
            e.target.closest('.bom-item-row').remove();
        }
    });
});
