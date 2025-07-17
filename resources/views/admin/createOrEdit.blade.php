@extends('admin::admin.layouts.master')

@section('title',  $role->name . 's Management')

@section('page-title', isset($user) ? 'Edit '.$role->name : 'Create '.$role->name)

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.users.index', ['type' => $type]) }}">Manage {{$role->name}}s</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{isset($user) ? 'Edit '.$role->name : 'Create '.$role->name}}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start user Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{ isset($user) ? route('admin.users.update', ['type' => $type, 'user' => $user->id]) : route('admin.users.store', ['type' => $type]) }}"
                        method="POST" id="userForm">
                        @if (isset($user))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>First Name<span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control alphabets-only"
                                        value="{{ $user?->first_name ?? old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>Last Name<span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control alphabets-only"
                                        value="{{ $user?->last_name ?? old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>Email<span class="text-danger">*</span></label>
                                    <input type="text" name="email" class="form-control"
                                        value="{{ $user?->email ?? old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <div class="form-group">
                                    <label>Mobile<span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" class="form-control numbers-only"
                                        value="{{ $user?->mobile ?? old('mobile') }}" required>
                                    @error('mobile')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        <option value="1" {{ (($user?->status ?? old('status')) == '1') ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ (($user?->status ?? old('status')) == '0') ? 'selected' : '' }}>InActive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                            <a href="{{ route('admin.users.index', ['type' => $type]) }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End user Content -->
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS for the user -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">           
@endpush

@push('scripts')
    <!-- Then the jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Select2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   
    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            $.validator.addMethod(
                "alphabetsOnly",
                function (value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                },
                "Please enter letters only"
            );

            $.validator.addMethod(
                "customEmail",
                function (value, element) {
                    return (
                        this.optional(element) ||
                        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)
                    );
                },
                "Please enter a valid email address"
            );

            //jquery validation for the form
            $('#userForm').validate({
                ignore: [],
                rules: {
                    first_name: {
                        required: true,
                        minlength: 3,
                        alphabetsOnly: true
                    },
                    last_name: {
                        required: true,
                        minlength: 3,
                        alphabetsOnly: true
                    },
                    email: {
                        required: true,
                        email: true,
                        customEmail: true
                    },
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 7,
                        maxlength: 15,
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter first name",
                        minlength: "First name must be at least 3 characters long"
                    },
                    last_name: {
                       required: "Please enter last name",
                       minlength: "Last name must be at least 3 characters long"
                    },
                    email: {
                        required: "Please enter email",
                        email: "Please enter a valid email address"
                    },
                    mobile: {
                        required: "Please enter mobile no.",
                        digits: "Please enter a valid mobile number",
                        minlength: "Mobile number must be at least 7 digits long",
                        maxlength: "Mobile number must not exceed 15 digits"
                    }
                },
                submitHandler: function(form) {
                    const $btn = $('#saveBtn');
                    $btn.prop('disabled', true).text('Saving...');
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    error.insertAfter(element);
                }
            });
        });
    </script>
@endpush
