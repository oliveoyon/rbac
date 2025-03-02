@extends('dashboard.layouts.admin-layout')

@section('title', 'District Management')

@push('styles')
<style>
    /* Modal Background */
.modal-content {
border-radius: 10px;
box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
border: none;
transition: all 0.3s ease-in-out;
background-color: #f8f9fa; /* Light gray background */
}

/* Modal Header */
.modal-header {
background-color: #c30f08; /* Your primary red */
color: white;
border-top-left-radius: 10px;
border-top-right-radius: 10px;
padding: 15px;
}

/* Modal Title */
.modal-title {
font-size: 18px;
font-weight: bold;
}

/* Close Button */
.modal-header .btn-close {
color: white;
filter: invert(1);
opacity: 0.8;
}
.modal-header .btn-close:hover {
opacity: 1;
}

/* Modal Body */
.modal-body {
padding: 20px;
}

/* Form Inputs */
.modal-body input,
.modal-body select,
.modal-body textarea {
width: 100%;
padding: 10px;
margin-bottom: 10px;
border-radius: 5px;
border: 1px solid #ccc;
transition: border 0.2s, box-shadow 0.2s;
background-color: white;
}

.modal-body input:focus,
.modal-body select:focus,
.modal-body textarea:focus {
border-color: #c30f08;
outline: none;
box-shadow: 0 0 5px rgba(195, 15, 8, 0.4);
}

/* Modal Footer */
.modal-footer {
padding: 15px;
border-top: 1px solid #ddd;
border-bottom-left-radius: 10px;
border-bottom-right-radius: 10px;
background-color: #e9ecef; /* Light gray footer */
}

/* Buttons */
.modal-footer .btn {
padding: 8px 16px;
border-radius: 5px;
font-weight: bold;
transition: all 0.3s;
}

/* Primary Button */
.modal-footer .btn-primary {
background-color: #c30f08;
border: none;
}
.modal-footer .btn-primary:hover {
background-color: #9d0c06;
}

/* Cancel Button */
.modal-footer .btn-secondary {
background-color: #6c757d; /* Gray */
border: none;
}
.modal-footer .btn-secondary:hover {
background-color: #545b62;
}

/* Responsive Modal */
@media (max-width: 576px) {
.modal-dialog {
    max-width: 90%;
}
}

</style>
@endpush

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <button class="btn btn-success btn-sm" id="createDistrictBtn"><i class="fas fa-plus-square mr-1"></i> Add New District</button>
                    
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
                            <td>{{ $loop->iteration }}</td>
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
                                <input type="text" class="form-control" id="districtName" name="name" >
                            </div>
                            <div class="mb-3 text-end">
                                <button type="submit" class="btn btn-success" id="submitBtn">Save</button>
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
    document.getElementById('createDistrictBtn').addEventListener('click', function() {
        document.getElementById('districtForm').reset();
        document.getElementById('districtForm').setAttribute('action', '{{ route('districts.add') }}');
        document.getElementById('districtForm').setAttribute('method', 'POST');
        document.getElementById('districtModalLabel').textContent = 'Add New District';
        var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
        districtModal.show();
    });

    document.querySelectorAll('.editDistrictBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            var districtId = this.getAttribute('data-id');
            var districtName = this.getAttribute('data-name');

            document.getElementById('districtName').value = districtName;
            document.getElementById('districtModalLabel').textContent = 'Edit District';
            document.getElementById('districtForm').setAttribute('action', '/dashboard/districts/' + districtId);
            document.getElementById('districtForm').setAttribute('method', 'POST');

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = 'PUT';
            document.getElementById('districtForm').appendChild(input);

            var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
            districtModal.show();
        });
    });

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
                                document.getElementById('district-' + districtId).remove();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The district has been deleted.',
                                    icon: 'success',
                                    position: 'top-end',
                                    toast: true,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                            } else {
                                Swal.fire('Error!', 'There was an error deleting the district.', 'error');
                            }
                        });
                }
            });
        });
    });

    document.getElementById('districtForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var submitButton = document.querySelector('#submitBtn');
        submitButton.disabled = true;

        var action = this.getAttribute('action');
        var method = this.getAttribute('method');
        var formData = new FormData(this);
        var districtModalElement = document.getElementById('districtModal');
        var districtModal = bootstrap.Modal.getInstance(districtModalElement);

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
                    if (districtModal) {
                        districtModal.hide(); // Hide modal first
                    }

                    // Show Swal message for at least 2 seconds
                    let swalInstance = Swal.fire({
                        title: 'Success!',
                        text: method === 'POST' ? 'District added successfully.' : 'District updated successfully.',
                        icon: 'success',
                        position: 'top-end',
                        toast: true,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });

                    // Update UI instantly
                    setTimeout(() => {
                        if (method === 'POST') {
                            location.reload(); // Reload page after Swal message finishes
                        } else {
                            let districtRow = document.getElementById('district-' + data.id);
                            if (districtRow) {
                                districtRow.querySelector('.district-name').textContent = formData.get('district_name');
                            }
                        }
                    }, 500); // Delay UI update slightly for smoothness

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
                submitButton.disabled = false;
            });
    });
</script>

@endpush
