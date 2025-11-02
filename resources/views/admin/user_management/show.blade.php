@extends('layouts.admin')
@section('content')
<br><br>
<div class="container">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Show User Details
                        <a href="{{ url('admin/user_management') }}" class="btn btn-primary float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <p>{{$userManagement->name}}</p>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" value="{{$userManagement->email}}" class="form-label">Email</label>
                        <p>{{$userManagement->enail}}</p>

                    </div>


                    <!-- User Level Field -->
                    <div class="mb-3">
                        <label for="role" class="form-label">User Level</label>
                        <p>
                            @if($userManagement->role == '0')
                                Admin
                            @elseif($userManagement->role == '1')
                                Staff
                            @elseif($userManagement->role == '2')
                                Subscriber
                            @elseif($userManagement->role == '3')
                                Customer
                            @endif
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection