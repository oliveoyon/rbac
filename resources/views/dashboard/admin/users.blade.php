@extends('dashboard.layouts.admin-layout')

@section('title', 'User Management')

@section('content')
    <div class="container">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openCreateModal()">Create User</button>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>District</th>
                    <th>PNGO</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->district ? $user->district->name : 'No District' }}</td>
                        <td>{{ $user->pngo ? $user->pngo->name : 'No PNGO' }}</td>
                        <td>{{ ucfirst($user->status) }}</td>
                        <td>
                            <button class="btn btn-warning editBtn" onclick="openEditModal({{ $user->id }})">Edit</button>
                            <button class="btn btn-danger deleteBtn" onclick="deleteUser({{ $user->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <!-- Modal for Creating/Editing User -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" id="user_id" name="user_id">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <span class="text-danger" id="nameError"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <span class="text-danger" id="emailError"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">District</label>
                            <select class="form-select" id="district_id" name="district_id" required>
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="districtError"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PNGO</label>
                            <select class="form-select" id="pngo_id" name="pngo_id" required>
                                <option value="">Select PNGO</option>
                                @foreach ($pngos as $pngo)
                                    <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="pngoError"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <span class="text-danger" id="statusError"></span>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $('.text-danger').html(''); // Clear previous errors

                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.name) $('#nameError').text(errors.name[0]);
                            if (errors.email) $('#emailError').text(errors.email[0]);
                            if (errors.district_id) $('#districtError').text(errors.district_id[0]);
                            if (errors.pngo_id) $('#pngoError').text(errors.pngo_id[0]);
                            if (errors.status) $('#statusError').text(errors.status[0]);
                        }
                    }
                });
            });
        });
    </script>
@endsection
