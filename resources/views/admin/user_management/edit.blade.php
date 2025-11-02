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
                    <h4>Edit User
                        <a href="{{ url('admin/user_management') }}" class="btn btn-primary float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{route('user_management.update', $userManagement->id)}}" method="POST">
                        @csrf <!-- Include CSRF token for security -->
                        @method('PUT')


                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" value="{{$userManagement->name}}" id="name" name="name"
                                class="form-control" placeholder="Enter name" required>
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" value="{{$userManagement->email}}" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email"
                                required>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password  class=" form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter password" required>
                        </div>

                        <!-- User Level Field -->
                        <div class="mb-3">
                            <label for="role" class="form-label">User Level</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="" selected disabled>Select a role</option>
                                <option value="0" {{ $userManagement->role == 0 ? 'selected' : '' }}>Admin</option>
                                <option value="1" {{ $userManagement->role == 1 ? 'selected' : '' }}>Staff</option>
                                <option value="2" {{ $userManagement->role == 2 ? 'selected' : '' }}>Subscriber</option>
                                <option value="3" {{ $userManagement->role == 3 ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>


                        <!-- Submit Button -->
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection