@extends('layouts.app')

@section('title', 'Edit Interview')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Interview</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recruitment.interviews') }}" class="text-decoration-none">Interviews</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Interview Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('recruitment.interviews.update', $interview->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="col-md-8 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Candidate <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="candidate_id" required>
                                    <option value="">Select Candidate</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id', $interview->candidate_id) == $candidate->id ? 'selected' : '' }}>{{ $candidate->full_name }} - {{ $candidate->email }}</option>
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
                                        <option value="{{ $job->id }}" {{ old('job_opening_id', $interview->job_opening_id) == $job->id ? 'selected' : '' }}>{{ $job->job_title }}</option>
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
                                    <option value="phone" {{ old('interview_type', $interview->interview_type) == 'phone' ? 'selected' : '' }}>Phone</option>
                                    <option value="video" {{ old('interview_type', $interview->interview_type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="in-person" {{ old('interview_type', $interview->interview_type) == 'in-person' ? 'selected' : '' }}>In-Person</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Scheduled At <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="datetime-local" class="form-control" name="scheduled_at" value="{{ old('scheduled_at', $interview->scheduled_at ? \Carbon\Carbon::parse($interview->scheduled_at)->format('Y-m-d\TH:i') : '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Location</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="location" value="{{ old('location', $interview->location) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Meeting Link</label>
                            <div class="col-md-7">
                                <input type="url" class="form-control" name="meeting_link" value="{{ old('meeting_link', $interview->meeting_link) }}" placeholder="https://...">
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
                                        <option value="{{ $interviewer->id }}" {{ old('interviewer_id', $interview->interviewer_id) == $interviewer->id ? 'selected' : '' }}>{{ $interviewer->name }}</option>
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
                                    <option value="scheduled" {{ old('status', $interview->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in-progress" {{ old('status', $interview->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $interview->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $interview->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="rescheduled" {{ old('status', $interview->status) == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                    <option value="no-show" {{ old('status', $interview->status) == 'no-show' ? 'selected' : '' }}>No Show</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-3" style="height: 70px;">Interview Notes</label>
                            <div class="col-md-9">
                                <textarea class="form-control" style="height: 70px;" name="interview_notes" rows="3">{{ old('interview_notes', $interview->interview_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-3" style="height: 70px;">Feedback</label>
                            <div class="col-md-9">
                                <textarea class="form-control" style="height: 70px;" name="feedback" rows="3">{{ old('feedback', $interview->feedback) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Rating (1-5)</label>
                            <div class="col-md-7">
                                <input type="number" class="form-control" name="rating" min="1" max="5" value="{{ old('rating', $interview->rating) }}">
                            </div>
                        </div>
                    </div>
                    @if($interview->status == 'cancelled')
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-5" style="height: 60px;">Cancellation Reason</label>
                            <div class="col-md-7">
                                <textarea class="form-control" style="height: 60px;" name="cancellation_reason" rows="2">{{ old('cancellation_reason', $interview->cancellation_reason) }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="d-flex gap-2 justify-content-center mt-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('recruitment.interviews') }}" class="btn btn-light">Cancel</a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
