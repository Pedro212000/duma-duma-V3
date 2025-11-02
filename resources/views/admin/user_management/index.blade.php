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
                    <h4>User List

                        <a href="{{url('admin/user_management/create')}}" class="btn btn-primary float-end">Add User</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>User Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_management as $user_detail)
                                <tr>
                                    <td hidden>{{$user_detail->id}}</td>
                                    <td>{{$user_detail->name}}</td>
                                    <td>{{$user_detail->email}}</td>
                                    <td>
                                        @if($user_detail->role == '0')
                                            <span class="badge bg-success">Administrator</span>
                                        @elseif($user_detail->role == '1')
                                            <span class="badge bg-primary">Staff</span>
                                        @elseif($user_detail->role == '2')
                                            <span class="badge bg-secondary">Subscriber</span>
                                        @elseif($user_detail->role == '3')
                                            <span class="badge bg-dark">Customer</span>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{route('user_management.edit', $user_detail->id)}}"
                                            class="btn btn-success">Edit</a>
                                        <a href="{{route('user_management.show', $user_detail->id)}}"
                                            class="btn btn-info">Show</a>

                                        <form action="{{ route('user_management.destroy', $user_detail->id) }}"
                                            method="POST" id="delete-form" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete()">Delete</button>
                                        </form>

                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <script>
                                            function confirmDelete() {
                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: "This action cannot be undone!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'Yes, delete it!',
                                                    cancelButtonText: 'Cancel'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        // If confirmed, submit the form
                                                        document.getElementById('delete-form').submit();
                                                    }
                                                });
                                            }
                                        </script>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $user_management->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection