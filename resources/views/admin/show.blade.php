@extends('admin::admin.layouts.master')

@section('title', 'Users Management')

@section('page-title', 'User Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.users.index') }}">Manage Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">User Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start User Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">                    
                    <div class="table-responsive">
                         <div class="card-body">      
                            <table class="table table-responsive-lg table-no-border">
                                <tbody>
                                    <tr>
                                        <th scope="row">Name</th>
                                        <td scope="col">{{ $user->full_name ?? 'N/A' }}</td>
                                    </tr>         
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td scope="col">{{ $user->email ?? 'N/A' }}</td>
                                    </tr>                                
                                    <tr>
                                        <th scope="row">Mobile</th>
                                        <td scope="col">{!! $user->mobile ?? 'N/A' !!}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td scope="col"> {!! config('admin.constants.aryStatusLabel.' . $user->status, 'N/A') !!}</td>
                                    </tr>                                
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col">{{ $user->created_at ?? 'N/A' }}</td>
                                    </tr>                                
                                </tbody>
                            </table>   
                                             
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End user Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
