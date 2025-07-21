@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Create New Task</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Validation Error:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Task Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Task Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="scheduled_date" class="form-label">Scheduled Date</label>
            <input type="date" name="scheduled_date" class="form-control" required value="{{ old('scheduled_date') }}">
        </div>

        <div class="mb-3">
            <label for="average_duration_minutes" class="form-label">Average Duration (minutes)</label>
            <input type="number" name="average_duration_minutes" class="form-control" min="1" required value="{{ old('average_duration_minutes') }}">
        </div>

        <hr>
        <h5>Required Positions</h5>
        <p>Add the positions and number of workers needed for this task:</p>

        <div id="positions-container">
            <div class="row g-2 mb-2 position-row">
                <div class="col-md-6">
                    <select name="positions[0][position_id]" class="form-control" required>
                        <option value="">Select Position</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="positions[0][required_count]" class="form-control" placeholder="No. of workers" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-row">Remove</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-position-btn" class="btn btn-secondary mb-3">Add Position</button>

        <button type="submit" class="btn btn-primary mt-3">Create Task</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let positionIndex = 1;

    document.getElementById('add-position-btn').addEventListener('click', function () {
        const container = document.getElementById('positions-container');
        const row = document.createElement('div');
        row.classList.add('row', 'g-2', 'mb-2', 'position-row');

        row.innerHTML = `
            <div class="col-md-6">
                <select name="positions[${positionIndex}][position_id]" class="form-control" required>
                    <option value="">Select Position</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="positions[${positionIndex}][required_count]" class="form-control" placeholder="No. of workers" min="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-remove-row">Remove</button>
            </div>
        `;

        container.appendChild(row);
        positionIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-row')) {
            e.target.closest('.position-row').remove();
        }
    });
</script>
@endpush