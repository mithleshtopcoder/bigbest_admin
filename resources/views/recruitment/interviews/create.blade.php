@extends('layouts.app')

@section('title', 'Schedule Interview')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Schedule Interview</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recruitment.interviews') }}" class="text-decoration-none">Interviews</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Schedule</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Interview Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('recruitment.interviews.store') }}" method="POST">
                @csrf

                <div class="col-md-8 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Candidate <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="candidate_id" required>
                                    <option value="">Select Candidate</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>{{ $candidate->full_name }} - {{ $candidate->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                      
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Job Opening</label>
                            <div class="col-md-7">      
                                <select class="form-select form-control" name="job_opening_id">
                                    <option value="">Select</option>
                                    @foreach($jobOpenings as $job)
                                        <option value="{{ $job->id }}" {{ old('job_opening_id') == $job->id ? 'selected' : '' }}>{{ $job->job_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Interview Type</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="interview_type">
                                    <option value="phone" {{ old('interview_type') == 'phone' ? 'selected' : '' }}>Phone</option>
                                    <option value="video" {{ old('interview_type') == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="in-person" {{ old('interview_type') == 'in-person' ? 'selected' : '' }}>In-Person</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Scheduled At <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="datetime-local" class="form-control" name="scheduled_at" value="{{ old('scheduled_at') }}" required>
                            </div>
                        </div>
                    </div>



                  
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Location</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="location" value="{{ old('location') }}">
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Meeting Link</label>
                            <div class="col-md-7">
                                <input type="url" class="form-control" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Interviewer</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="interviewer_id">
                                    <option value="">Select</option>
                                    @foreach($interviewers as $interviewer)
                                        <option value="{{ $interviewer->id }}" {{ old('interviewer_id') == $interviewer->id ? 'selected' : '' }}>{{ $interviewer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Status</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="status">
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-2">
                    <button type="submit" class="btn btn-primary">Schedule</button>
                    <a href="{{ route('recruitment.interviews') }}" class="btn btn-light">Cancel</a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
