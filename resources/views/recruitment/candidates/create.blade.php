@extends('layouts.app')

@section('title', 'Add Candidate')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Add Candidate</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recruitment.candidates') }}" class="text-decoration-none">Candidates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Candidate Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('recruitment.candidates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-8 mx-auto">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                            </div>
                            <label class="form-label col-md-2">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Email <span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                            <label class="form-label col-md-2">Phone</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Alternate Phone</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="alternate_phone" value="{{ old('alternate_phone') }}">
                            </div>
                            <label class="form-label col-md-2">Date of Birth</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Gender</label>
                            <div class="col-md-4">
                                <select class="form-select form-control" name="gender">
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <label class="form-label col-md-2">Job Opening</label>
                            <div class="col-md-4">
                                <select class="form-select form-control" name="job_opening_id">
                                    <option value="">Select</option>
                                    @foreach($jobOpenings as $job)
                                    <option value="{{ $job->id }}" {{ old('job_opening_id') == $job->id ? 'selected' : '' }}>{{ $job->job_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 70px;">Address</label>
                            <div class="col-md-10">
                                <textarea class="form-control" style="height: 70px;" name="address" rows="3">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">City</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                            </div>
                            <label class="form-label col-md-2">State</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="state" value="{{ old('state') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Pincode</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="pincode" value="{{ old('pincode') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Resume</label>
                            <div class="col-md-4">
                                <input type="file" class="form-control" name="resume_file" accept=".pdf,.doc,.docx">
                                <small class="text-muted">PDF, DOC, DOCX (Max 10MB)</small>
                            </div>
                            <label class="form-label col-md-2">Status</label>
                            <div class="col-md-4">
                                <select class="form-select form-control" name="status">
                                    <option value="applied" {{ old('status') == 'applied' ? 'selected' : '' }}>Applied</option>
                                    <option value="screening" {{ old('status') == 'screening' ? 'selected' : '' }}>Screening</option>
                                    <option value="shortlisted" {{ old('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="interviewed" {{ old('status') == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                    <option value="offered" {{ old('status') == 'offered' ? 'selected' : '' }}>Offered</option>
                                    <option value="hired" {{ old('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="withdrawn" {{ old('status') == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 110px;">Cover Letter</label>
                            <div class="col-md-10">
                                <textarea class="form-control" style="height: 110px;" name="cover_letter" rows="4">{{ old('cover_letter') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 70px;">Notes</label>
                            <div class="col-md-10">
                                <textarea class="form-control" style="height: 70px;" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('recruitment.candidates') }}" class="btn btn-light">Cancel</a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
