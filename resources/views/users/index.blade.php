<!-- resources/views/users/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Users</h1>
    <div class="mb-2">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
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
                    <button class="btn btn-sm btn-info" onclick="openEditModal({{ $user->id }})">Edit</button>
                    <button class="btn btn-sm btn-secondary" onclick="openViewModal({{ $user->id }})">View</button>
                    <button class="btn btn-sm btn-danger" onclick="openDeleteModal({{ $user->id }})">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for Add/Edit -->
<div class="modal" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Content will be loaded dynamically through JavaScript -->
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Content will be loaded dynamically through JavaScript -->
        </div>
    </div>
</div>

<script>
    // Define JavaScript functions to handle modals and AJAX requests
    // (Implement the functions as per your specific requirements)
    function openEditModal(userId) {
        // Fetch user data using AJAX and populate the modal form with the data
        // You can use jQuery or plain JavaScript for AJAX requests and form population
        // ...

        // Display the modal
        $('#userModal').modal('show');
    }

    function openViewModal(userId) {
        // Fetch user data using AJAX and populate the modal with the data
        // ...

        // Display the modal
        $('#userModal').modal('show');
    }

    function openDeleteModal(userId) {
        // Fetch user data using AJAX to confirm deletion
        // ...

        // Display the delete confirmation modal
        $('#deleteModal').modal('show');
    }
</script>
@endsection
