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
                    <h4>Create User
                        <a href="{{ url('admin/user_management') }}" class="btn btn-primary float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{route('user_management.store')}}" method="POST">
                        @csrf <!-- Include CSRF token for security -->

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name"
                                required>
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email"
                                required>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter password" required>
                        </div>

                        <!-- User Level Field -->
                        <div class="mb-3">
                            <label for="role" class="form-label">User Level</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="" selected disabled>Choose user level</option>
                                <option value="0">Admin</option>
                                <option value="1">Staff</option>
                                <option value="2">Subscriber</option>
                                <option value="3">Customer</option>
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