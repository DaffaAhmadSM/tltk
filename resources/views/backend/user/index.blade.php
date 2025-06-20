@extends('backend.layout-backend')

@section('content')
    <style>
        .select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .select:focus {
            border-color: #007bff;
            outline: none;
        }
    </style>

    <div class="container my-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="bg-light shadow-sm rounded p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title">Manage Users</h5>
                    <button type="button" class="btn btn-primary" onclick="showCreateModal()">
                        <i class="fas fa-plus"></i> Add User
                    </button>
                </div>

                <div class="form-group mb-3">
                    <label for="filterRole" class="form-label">Filter Role</label>
                    <select class="select form-select" id="filterRole">
                        <option value="" disabled selected>-- Select Role --</option>
                        @foreach($groupedRole as $role)
                            <option value="{{ $role->UR_ID }}">{{ $role->ROLE_NAME }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="table-light">
                        <tr>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>User Address</th>
                            <th>User Phone</th>
                            <th>Last Login</th>
                            <th>User Role</th>
                            <th>User Sex</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createUserForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">User  Name <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <input type="text" id="username" name="U_NAME" class="form-control" placeholder="Enter user name" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">User  Role <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <select id="role" name="UR_ID" class="form-control select2" required>
                                <option value="" disabled selected>Select Role</option>
                                @foreach($groupedRole as $role)
                                    <option value="{{ $role->UR_ID }}">{{ $role->ROLE_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sex" class="form-label">User  Sex <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <select id="sex" name="U_SEX" class="form-control select2" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <input type="email" id="email" name="U_EMAIL" class="form-control" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <input type="text" id="phone" name="U_PHONE" class="form-control" placeholder="Enter phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <textarea id="address" name="U_ADDRESS" class="form-control" rows="3" placeholder="Enter address" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" id="image" name="U_IMAGE_PROFILE" class="form-control" onchange="previewImage(this)">
                            <div id="imagePreview"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <div class="input-group">
                                <input type="password" id="password" name="U_PASSWORD" class="form-control" required>
                                <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                    <i id="eyeIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="EDIT_U_ID" name="U_ID"> <!-- Hidden field for editing -->

                        <div class="mb-3">
                            <label for="EDIT_USERNAME" class="form-label">User  Name <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <input type="text" id="EDIT_USERNAME" name="U_NAME" class="form-control" placeholder="Enter user name" required>
                        </div>
                        <div class="mb-3">
                            <label for="EDIT_ROLE" class="form-label">User  Role <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <select id="EDIT_ROLE" name="UR_ID" class="form-control select2">
                                <option value="" disabled selected>Select Role</option>
                                @foreach($groupedRole as $role)
                                    <option value="{{ $role->UR_ID }}">{{ $role->ROLE_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="EDIT_SEX" class="form-label">User  Sex <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <select id="EDIT_SEX" name="U_SEX" class="form-control select2">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="EDIT_EMAIL" class="form-label">Email <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <input type="email" id="EDIT_EMAIL" name="U_EMAIL" class="form-control" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="EDIT_PHONE" class="form-label">Phone <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <input type="text" id="EDIT_PHONE" name="U_PHONE" class="form-control" placeholder="Enter phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="EDIT_ADDRESS" class="form-label">Address <svg width="8px" height="8px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff060d"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 4V20M19 7L5 17M5 7L19 17" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></label>
                            <textarea id="EDIT_ADDRESS" name="U_ADDRESS" class="form-control" rows="3" placeholder="Enter address" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="U_IMAGE_PROFILE" class="form-label">Image</label>
                            <input type="file" id="U_IMAGE_PROFILE" name="U_IMAGE_PROFILE" class="form-control" onchange="previewImage(this)">
                            <div id="EDIT_IMAGE_PREVIEW"></div>
                        </div>
                        <div class="mb-3">
                            <label for="EDIT_PASSWORD" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="EDIT_PASSWORD" name="U_PASSWORD" class="form-control">
                                <button type="button" id="toggleEditPassword" class="btn btn-outline-secondary">
                                    <i id="editEyeIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const USER_TOKEN = '{{ $token }}';

        function previewImage(input) {
            const file = input.files[0];
            const previewContainer = input.id === 'image' ? '#imagePreview' : '#EDIT_IMAGE_PREVIEW'; // Determine which preview to use

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(previewContainer).html('<img src="' + e.target.result + '" alt="Image Preview" class="img-thumbnail" width="100">');
                }
                reader.readAsDataURL(file);
            } else {
                $(previewContainer).html(''); // Clear the preview if no file is selected
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'asc']],
                iDisplayLength: 50,
                bLengthChange: true,
                bFilter: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    url: "{{ url('backend/users/datatables') }}",
                    type: "POST",
                    data: function (d) {
                        d.groupRole = $('#filterRole').val();
                    }
                },
                columns: [
                    { data: "User Name" },
                    { data: "User Email" },
                    { data: "User Address" },
                    { data: "User Phone" },
                    { data: "Last Login" },
                    { data: "User Role" },
                    { data: "User Sex" },
                    { data: "Action" },
                ],
                dom: '<"datatable-header"lf>t<"datatable-footer"ip>',
                responsive: true,
                language: {
                    search: '<span class="text-xs font-medium mb-4 mt-4">Search:</span> _INPUT_',
                    lengthMenu: '<span class="text-xs font-medium mb-4">Show:</span> _MENU_',
                    paginate: {
                        first: '<button class="btn btn-secondary btn-sm">First</button>',
                        last: '<button class="btn btn-secondary btn-sm">Last</button>',
                        next: '<button class="btn btn-secondary btn-sm">&rarr;</button>',
                        previous: '<button class="btn btn-secondary btn-sm">&larr;</button>'
                    },
                    info: '<span class="text-xs">Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> entries</span>',
                    emptyTable: '<i class="text-xs">No data available</i>'
                },
            });
            $('#filterRole').on('change', function() {
                var table = $('#dataTable').DataTable();
                table.ajax.reload();
            });
            $('#image').on('change', function () {
                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imagePreview').html(`
                    <img src="${e.target.result}" alt="Image Preview"
                         class="img-thumbnail" style="max-width: 100%; height: auto;">
                `);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreview').html('<p class="text-danger">Invalid image file.</p>');
                }
            });
        });

        function showCreateModal() {
            $('#modalTitle').text('Create New User');
            $('#createUserForm')[0].reset(); // Reset the create form
            $('#createUserModal').modal('show');
        }

        function editData(rowData) {
            $('#modalTitle').text('Edit User');
            $('#EDIT_U_ID').val(rowData.U_ID).data('original-value', rowData.U_ID);
            $('#EDIT_USERNAME').val(rowData.U_NAME).data('original-value', rowData.U_NAME);
            $('#EDIT_ROLE').val(rowData.UR_ID).data('original-value', rowData.UR_ID);
            $('#EDIT_SEX').val(rowData.U_SEX).data('original-value', rowData.U_SEX);
            $('#EDIT_EMAIL').val(rowData.U_EMAIL).data('original-value', rowData.U_EMAIL);
            $('#EDIT_PHONE').val(rowData.U_PHONE).data('original-value', rowData.U_PHONE);
            $('#EDIT_ADDRESS').val(rowData.U_ADDRESS).data('original-value', rowData.U_ADDRESS);
            $('#EDIT_PASSWORD').val('').data('original-value', '');


            if (rowData.U_IMAGE_PROFILE) {
                $('#EDIT_IMAGE_PREVIEW').html('<img src="' + '{{ URL::asset('storage/') }}' + '/' + rowData.U_IMAGE_PROFILE + '" alt="User  Image" class="img-thumbnail" width="100">');
            } else {
                $('#EDIT_IMAGE_PREVIEW').html('');
            }

            $('#editUserModal').modal('show');
        }

        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = '/backend/users/create'; // Adjust this URL to your actual endpoint
            createOrUpdateData(formData, url, 'createUserModal');
        });

        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            const originalData = {
                U_NAME: $('#EDIT_USERNAME').data('original-value'),
                UR_ID: $('#EDIT_ROLE').data('original-value'),
                U_SEX: $('#EDIT_SEX').data('original-value'),
                U_EMAIL: $('#EDIT_EMAIL').data('original-value'),
                U_PHONE: $('#EDIT_PHONE').data('original-value'),
                U_ADDRESS: $('#EDIT_ADDRESS').data('original-value'),
                U_PASSWORD: $('#EDIT_PASSWORD').data('original-value'),
                U_IMAGE_PROFILE: $('#EDIT_IMAGE_PREVIEW img').length ? $('#EDIT_IMAGE_PREVIEW img').attr('src') : null, // Original image URL
            };

            const newData = {
                U_NAME: $('#EDIT_USERNAME').val(),
                UR_ID: $('#EDIT_ROLE').val(),
                U_SEX: $('#EDIT_SEX').val(),
                U_EMAIL: $('#EDIT_EMAIL').val(),
                U_PHONE: $('#EDIT_PHONE').val(),
                U_ADDRESS: $('#EDIT_ADDRESS').val(),
                U_PASSWORD: $('#EDIT_PASSWORD').val(),
                U_IMAGE_PROFILE: $('#U_IMAGE_PROFILE').val() ? $('#U_IMAGE_PROFILE')[0].files[0] : null, // New image file
            };

            // Check if data has changed
            let hasChanged = false;

            Object.keys(newData).forEach((key) => {
                if (key === 'U_IMAGE_PROFILE') {
                    // Check if the image file name is different
                    if (newData[key] && (!originalData[key] || newData[key].name !== originalData[key].split('/').pop())) {
                        hasChanged = true;
                        formData.append(key, newData[key]); // Append only the new image
                    }
                } else if (newData[key] !== originalData[key]) {
                    hasChanged = true;
                    formData.append(key, newData[key]); // Append changed fields
                }
            });

            if (!hasChanged) {
                toastr.warning('No changes to save.');
                return; // Prevent form submission
            }

            const id = $('#EDIT_U_ID').val();
            const url = '/backend/users/' + id + '/update'; // Adjust this URL to your actual endpoint

            createOrUpdateData(formData, url, 'editUserModal');
        });

        function createOrUpdateData(formData, url, modalId) {
            createOverlay('Processing...'); // Show overlay while processing
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                processData: false,
                contentType: false,
                data: formData,
                success: function (data) {
                    if (data.STATUS === 'SUCCESS') {
                        toastr.success(data.MESSAGE);
                        gOverlay.hide();
                        $('#' + modalId).modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                    } else {
                        toastr.error(data.MESSAGE);
                        gOverlay.hide();
                    }
                },
                error: function (error) {
                    gOverlay.hide();
                    toastr.error('Network or server error: ' + error);
                },
            });
        }


        $(document).on('click', '.delete-action', function() {
            const userId = $(this).data('id');
            deleteData(userId);
        });

        function deleteData(U_ID) {
            createOverlay("Processing...");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan AJAX request untuk menghapus data
                    $.ajax({
                        type: "POST",
                        url: '/backend/users/' + U_ID + '/delete',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            gOverlay.hide();
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            );
                            // Reload DataTable
                         reloadDataTable()
                        },
                        error: function (error) {
                            gOverlay.hide();
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the data.',
                                'error'
                            );
                        }
                    });
                }
            });
        }


        function createOverlay(message) {
            $('body').append(`
            <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-50 flex items-center justify-center">
                <div class="text-white bg-gray-800 p-4 rounded">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">${message}</div>
                </div>
            </div>
        `);
        }

        var gOverlay = {
            hide: function() {
                $('#overlay').remove();
            }
        };

        $('#generatePassword').on('click', function() {
            const password = Password.generate(16);
            $('#password').val(password);
        });

        var Password = {
            _pattern: /[a-zA-Z0-9_\-\+\.]/,
            _getRandomByte: function () {
                if (window.crypto && window.crypto.getRandomValues) {
                    var result = new Uint8Array(1);
                    window.crypto.getRandomValues(result);
                    return result[0];
                } else if (window.msCrypto && window.msCrypto.getRandomValues) {
                    var result = new Uint8Array(1);
                    window.msCrypto.getRandomValues(result);
                    return result[0];
                } else {
                    return Math.floor(Math.random() * 256);
                }
            },

            generate: function(length) {
                return Array.apply(null, { 'length': length })
                    .map(function() {
                        var result;
                        while (true) {
                            result = String.fromCharCode(this._getRandomByte());
                            if (this._pattern.test(result)) {
                                return result;
                            }
                        }
                    }, this)
                    .join('');
            }
        };

        $('#togglePassword').on('click', function() {
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);

            // Change eye icon
            const eyeIcon = $('#eyeIcon');
            if (type === 'text') {
                eyeIcon.html(`
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825c-1.5.825-3.75.825-5.25 0C4.5 17.325 3 15.375 3 12c0-3.375 1.5-5.325 4.125-6.825 1.5-.825 3.75-.825 5.25 0C19.5 6.675 21 8.625 21 12c0 3.375-1.5 5.325-4.125 6.825z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            `);
            } else {
                eyeIcon.html(`
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12s2-4 9-4 9 4 9 4-2 4-9 4-9-4-9-4z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            `);
            }
        });

        function reloadDataTable() {
            var table = $('#dataTable').DataTable();
            $("#userModal").modal("hide");
            gOverlay.hide();
            table.ajax.reload();
        }

</script>
@endsection
