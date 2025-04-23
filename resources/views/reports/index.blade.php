@extends('layouts.app')
@section('title')
    Reports
@endsection
@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center bg-white mb-4 shadow-sm p-3 rounded">
        <h2>Reports</h2>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#createDepartmentReport" data-status="completed" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">View Department Report</button>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#createEmployeeReport" data-status="completed" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">View Employee Report</button>
        </div>
    </div>    


    <div class="modal fade" id="createDepartmentReport" tabindex="-1" aria-labelledby="createDepartmentReportLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('reports.create') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createDepartmentReportLabel">Create Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">


                            <div class="mb-3">
                                <label for="project_id" class="form-label">Employee</label>
                                <select name="project_id" id="project_id" class="form-select" required>
                                    <option selected disabled value="0"> -- Select Department -- </option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project-> id }}">{{ $project-> name }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <input type="hidden" name="status" id="task_status">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Report</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>

</div>

@endsection