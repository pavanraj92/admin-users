@extends('admin::admin.layouts.master')

@section('title', $role->name . 's Management')

@section('page-title', $role->name . ' Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a
            href="{{ route('admin.users.index', ['type' => $type]) }}">{{ $role->name }} Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $role->name }} Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Header with Back button -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $user->full_name ?? 'N/A' }} - {{ $role->name }}</h4>
                            <div>
                                <a href="{{ route('admin.users.index', ['type' => $type]) }}"
                                    class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- User Information -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">{{ $role->name }} Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Name:</label>
                                                    <p>{{ $user->full_name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Email:</label>
                                                    <p>{{ $user->email ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Mobile:</label>
                                                    <p>{{ $user->mobile ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status:</label>
                                                    <p>{!! config('user.constants.aryStatusLabel.' . $user->status, 'N/A') !!}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Created At:</label>
                                                    <p>
                                                        {{ $user->created_at ? $user->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            @admincan('users_manager_edit')
                                                <a href="{{ route('admin.users.edit', ['type' => $type, 'user' => $user]) }}"
                                                    class="btn btn-warning mb-2">
                                                    <i class="mdi mdi-pencil"></i> Edit {{ $role->name }}
                                                </a>
                                            @endadmincan

                                            @admincan('users_manager_delete')
                                                <button type="button" class="btn btn-danger delete-btn delete-record"
                                                    title="Delete this record"
                                                    data-url="{{ route('admin.users.destroy', ['type' => $type, 'user' => $user]) }}"
                                                    data-redirect="{{ route('admin.users.index', ['type' => $type]) }}"
                                                    data-text="Are you sure you want to delete this record?"
                                                    data-method="DELETE">
                                                    <i class="mdi mdi-delete"></i> Delete {{ $role->name }}
                                                </button>
                                            @endadmincan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
@endsection
