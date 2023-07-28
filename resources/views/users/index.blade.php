@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-warning">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-warning">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="row justify-content-center">

                <div class="mb-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create User</button>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $user->id }}">Edit</button>
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#viewModal{{ $user->id }}">View</button>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="viewModal{{ $user->id }}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModal{{ $user->id }}Label">View User:
                                                {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Name: {{ $user->name }}</p>
                                            <p>Email: {{ $user->email }}</p>
                                            <p>Created: {{ $user->created_at }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                aria-labelledby="editModal{{ $user->id }}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModal{{ $user->id }}Label">Edit User:
                                                {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('users.update', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="editName{{ $user->id }}"
                                                        class="form-label">Name</label>
                                                    <input type="text" class="form-control"
                                                        id="editName{{ $user->id }}" name="name"
                                                        value="{{ old('name', $user->name) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editEmail{{ $user->id }}"
                                                        class="form-label">Email</label>
                                                    <input type="email" class="form-control"
                                                        id="editEmail{{ $user->id }}" name="email"
                                                        value="{{ old('email', $user->email) }}" required>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="createName" name="name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="createEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="createEmail" name="email"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="createPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="createPassword" name="password"
                                value="{{ old('password') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="createPasswordConfirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="createPasswordConfirmation" name="password_confirmation"
                                value="{{ old('password_confirmation') }}" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
