@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create Quality Control Record</h3>

    <form action="{{ route('quality_control.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="production_batch_id" class="form-label">Production Batch ID</label>
            <input type="number" name="production_batch_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="passed">Passed</option>
                <option value="failed">Failed</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="inspection_date" class="form-label">Inspection Date</label>
            <input type="date" name="inspection_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="defect_count" class="form-label">Defect Count</label>
            <input type="number" name="defect_count" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <div class="mb-3" id="corrective-action-group" style="display: none;">
            <label for="corrective_action_taken" class="form-label">Corrective Action Taken</label>
            <textarea name="corrective_action_taken" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
