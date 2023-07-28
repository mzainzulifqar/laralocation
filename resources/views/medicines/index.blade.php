@extends('layouts.app')

@section('content')
    <div class="container">
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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create Medicine</button>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Medicines as $Medicine)
                        <tr>
                            <td>{{ $Medicine->name }}</td>
                            <td>{{ $Medicine->quantity }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $Medicine->id }}">Edit</button>
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#viewModal{{ $Medicine->id }}">View</button>
                                <form action="{{ route('medicines.destroy', $Medicine) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal{{ $Medicine->id }}" tabindex="-1"
                            aria-labelledby="viewModal{{ $Medicine->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewModal{{ $Medicine->id }}Label">View Medicine:
                                            {{ $Medicine->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Name: {{ $Medicine->name }}</p>
                                        <p>Quantity: {{ $Medicine->quantity }}</p>
                                        <p>Created: {{ $Medicine->created_at }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $Medicine->id }}" tabindex="-1"
                            aria-labelledby="editModal{{ $Medicine->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{ $Medicine->id }}Label">Edit Medicine:
                                            {{ $Medicine->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('medicines.update', $Medicine) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label for="editName{{ $Medicine->id }}" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="editName{{ $Medicine->id }}"
                                                    name="name" value="{{ old('name', $Medicine->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editQuantity{{ $Medicine->id }}" class="form-label">Quantity</label>
                                                <input type="text" class="form-control"
                                                    id="editQuantity{{ $Medicine->id }}" name="quantity"
                                                    value="{{ old('quantity', $Medicine->quantity) }}" required>
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
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="createName" name="name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="createQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="createQuantity" name="quantity"
                                value="{{ old('quantity') }}" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
