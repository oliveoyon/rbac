@extends('dashboard.layouts.admin-layout')

@section('title', 'District Management')

@push('styles')
    <!-- Add any additional styles here if needed -->
@endpush

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <button class="btn btn-primary" id="createDistrictBtn">Add New District</button>
                </div>
            </div>

            <!-- Districts Table -->
            <table class="table table-striped" id="districtsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- This is where you will loop through your districts -->
                    @foreach ($districts as $district)
                        <tr id="district-{{ $district->id }}">
                            <td>{{ $district->id }}</td>
                            <td>{{ $district->name }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editDistrictBtn" data-id="{{ $district->id }}"
                                    data-name="{{ $district->name }}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteDistrictBtn"
                                    data-id="{{ $district->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Fullscreen Modal for Create/Edit District -->
        <div class="modal fade" id="districtModal" tabindex="-1" aria-labelledby="districtModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="districtModalLabel">Add New District</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="districtForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="districtName" class="form-label">District Name</label>
                                <input type="text" class="form-control" id="districtName" name="name" required>
                            </div>
                            <div class="mb-3 text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Show Modal for Creating or Editing a District
    document.getElementById('createDistrictBtn').addEventListener('click', function() {
        document.getElementById('districtForm').reset(); // Reset the form for a new district
        document.getElementById('districtForm').setAttribute('action', 'districts'); // Set action to POST /districts
        document.getElementById('districtForm').setAttribute('method', 'POST'); // Use POST method
        document.getElementById('districtModalLabel').textContent = 'Add New District'; // Set the modal title
        var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
        districtModal.show();
    });

    // Show Modal for Editing a District
    document.querySelectorAll('.editDistrictBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            var districtId = this.getAttribute('data-id');
            var districtName = this.getAttribute('data-name');

            // Pre-fill the form with the current district data
            document.getElementById('districtName').value = districtName;
            document.getElementById('districtModalLabel').textContent = 'Edit District';

            // Change form action to PUT method for updating the district
            document.getElementById('districtForm').setAttribute('action', '/dashboard/districts/' + districtId);
            document.getElementById('districtForm').setAttribute('method', 'POST');

            // Add hidden method field to simulate PUT
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = 'PUT';
            document.getElementById('districtForm').appendChild(input);

            var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
            districtModal.show();
        });
    });

    // Delete District
    document.querySelectorAll('.deleteDistrictBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            var districtId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this district!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/districts/${districtId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The district has been deleted.',
                                    icon: 'success',
                                    position: 'top-end',
                                    toast: true,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                document.getElementById('district-' + districtId).remove(); // Remove the row directly
                            } else {
                                Swal.fire('Error!', 'There was an error deleting the district.', 'error');
                            }
                        });
                }
            });
        });
    });

    // Form Submission (Add or Edit)
    document.getElementById('districtForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var submitButton = document.querySelector('#districtForm button[type="submit"]');
        submitButton.disabled = true; // Disable the submit button to prevent double submit

        var action = this.getAttribute('action');
        var method = this.getAttribute('method');
        var formData = new FormData(this);
        var districtModal = new bootstrap.Modal(document.getElementById('districtModal')); // Get the modal instance

        // Hide the modal before sending the request
        districtModal.hide();

        fetch(action, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: method === 'POST' ? 'District added successfully.' : 'District updated successfully.',
                        icon: 'success',
                        position: 'top-end',
                        toast: true,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.href = '{{ route('dashboard.districts') }}'; // Redirect after success
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'There was an error processing your request.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Something went wrong!', 'error');
            })
            .finally(() => {
                submitButton.disabled = false; // Re-enable the submit button after request is complete
            });
    });

    // Success and Error Handling for SweetAlert notifications
    @if (session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            position: 'top-end',
            toast: true,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            Swal.fire({
                title: 'Error!',
                text: '{{ $error }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            // Highlight inputs with errors
            @foreach ($errors->keys() as $key)
                document.getElementById('{{ $key }}').style.border = '1px solid red';
            @endforeach
        @endforeach
    @endif
</script>

@endpush
