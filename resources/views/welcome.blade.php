<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD App Laravel 8 & Ajax</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css' />
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.css" />

</head>

<body>

    <div class="container   mt-5">
        @include('add')
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="POST" id="update_employee_form" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="border rounded p-4">
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div id="editModaldata">

                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-block" id="update">Update
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-danger p-3"><button type="button" class="btn btn-info" data-bs-toggle="modal"
                data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap"><i class="bi-plus-circle me-2"></i>
                User</button></div>
        <div class="card-body table-responsive" id="show_all_employees">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
        </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js'></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            fetchAllEmployees()
            $(document).on('click', '.add', function() {
                var html = `<div class="d-flex parent mt-2">
                <input type="text" name="experience[]" id="form6Example4" class="form-control" />
                &nbsp;<button type="button" class="btn btn-light remove" data-bs-toggle="modal"
                    data-bs-target="#addEmployeeModal"><i class="bi-dash-circle me-2"></i></button>
            </div>`;
                $('.add-input').append(html);
            });
            $(document).on('click', '.remove', function() {
                $(this).parents(".parent").remove();
            });

            //this is for update employee
            $("#update_employee_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#update").text('Updating..');
                var update = "{{ route('update') }}";
                $.ajax({
                    url: update,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            Swal.fire(
                                'Updated!',
                                'User Updated Successfully!',
                                'success'
                            )
                            fetchAllEmployees();
                        } else if (response.validation) {
                            printErrorMsg(response.validation);
                        } else if (response.error) {
                            Swal.fire(
                                'Updated!',
                                response.error,
                                'success'
                            )
                        }
                        $("#update").text('Update User');
                        $("#update_employee_form")[0].reset();
                        $('#editModal').modal('hide');
                    }
                });
            });

            //this is for add employee
            $("#add_employee_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#submit").text('Adding..');
                var store = "{{ route('store') }}";
                $.ajax({
                    url: store,
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            Swal.fire(
                                'Added!',
                                'User Added Successfully!',
                                'success'
                            )
                            fetchAllEmployees();
                        } else if (response.validation) {
                            printErrorMsg(response.validation);
                        } else if (response.error) {
                            Swal.fire(
                                'Added!',
                                response.error,
                                'success'
                            )
                        }
                        $("#submit").text('Add User');
                        $("#add_employee_form")[0].reset();
                        $('#exampleModal').modal('hide');
                    }
                });
            });

            //this is for delete employee
            $(document).on('click', '.deleteIcon', function() {
                var id = $(this).attr('id');
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
                        var del = "{{ route('delete') }}";
                        $.ajax({
                            method: 'get',
                            url: del,
                            data: {
                                id: id
                            },
                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    )
                                    fetchAllEmployees()
                                }
                            }
                        });
                    }
                });
            })

            //this is for edit for
            $(document).on('click', '.editIcon', function() {
                var id = $(this).attr('id');
                var edit = "{{ route('edit') }}";
                $.ajax({
                    method: 'get',
                    url: edit,
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $("#editModaldata").html(response);
                        $('#editModal').modal('show');
                    }
                });
            })

            //this is for display error message
            function printErrorMsg(msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display', 'block');
                $.each(msg, function(key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }
            
            // this is for fetching user data
            function fetchAllEmployees() {
                var fetch = "{{ route('fetchAll') }}";
                $.ajax({
                    url: fetch,
                    method: 'get',
                    success: function(response) {
                        $("#show_all_employees").html(response);
                        $("table").DataTable({
                            order: [0, 'desc']
                        });
                    }
                });
            }
        });
    </script>

</body>

</html>
