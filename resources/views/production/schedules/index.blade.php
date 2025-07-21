@extends('layouts.app')

@section('title', 'Production Schedules')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Production Schedules</h1>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
            <i class="bi bi-plus-circle me-1"></i> Create New Schedule
        </button>
    </div>

    @include('production.schedules.partials.schedule_table')
    @include('production.schedules.partials.create_modal')
    @include('production.schedules.partials.edit_modal')
</div>
@endsection
