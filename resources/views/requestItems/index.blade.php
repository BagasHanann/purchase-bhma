@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="box box-success">

        <div class="box-header">
            <h3 class="box-title">Daftar Permintaan Produk</h3>

            <a onclick="addForm()" class="btn btn-success pull-right" style="margin-top: -8px;"><i class="fa fa-plus"></i> Tambah Permintaan Produk</a>
        </div>


        <!-- /.box-header -->
        <div class="box-body">
            <table id="requestItems-table" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Kode</th>
                    <th>Petugas</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('requestItems.form')

@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#requestItems-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.request') }}",
            columns: [
                {data: 'formatted_id', name: 'formatted_id'},
                {data: 'user_name', name: 'user_name'},
                {data: 'product_name', name: 'product_name'},
                {data: 'stock', name: 'stock'},
                {data: 'status', name: 'status', render: function (data, type, row) {
                    if (data === 'Pending') {
                        return '<span class="label label-default" style="background-color: gray; color:white; font-size: 14px;">Pending</span>';
                    } else if (data === 'Approved') {
                        return '<span class="label label-success" style="background-color: green; color:white; font-size: 14px">Approved</span>';
                    } else if (data === 'Rejected') {
                        return '<span class="label label-danger" style="background-color: red; color:white; font-size: 14px">Rejected</span>';
                    }
                    return data;
                }},
                {data: 'action', name: 'action', 
                orderable: false, 
                searchable: false, 
                render: function(data, type, row) {
                    var actions = '';

                    var isAdmin = {{ Auth::user()->hasRole('admin') ? 'true' : 'false' }};
                    var isStaff = {{ Auth::user()->hasRole(['staff', 'field']) ? 'true' : 'false' }};

                    // Check if user is Admin and status is Pending
                    if (isAdmin && row.status === 'Pending') {
                        actions += '<button onclick="updateStatus(' + row.id + ', \'approved\')" style="font-size: 14px;" class="btn btn-success btn-sm">Approve</button>';
                        actions += ' <button onclick="updateStatus(' + row.id + ', \'rejected\')" style="font-size: 14px;" class="btn btn-danger btn-sm">Reject</button>';
                    }

                    // Check if user is staff
                    if (isStaff) {
                        actions += '<a onclick="editForm(' + row.id + ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
                        actions += ' <a onclick="deleteData(' + row.id + ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                    }

                    return actions;
                }}
            ],
            rowCallback: function(row, data, index) {
                if (data.status !== 'Pending') {
                    $(row).find('td:last').hide();
                }
            },
            drawCallback: function(settings) {
                var api = this.api();
                // Check if all rows are non-pending, hide the column header if so
                var allNonPending = api.rows().data().toArray().every(function(row) {
                    return row.status !== 'Pending';
                });

                if (allNonPending) {
                    api.columns(-1).visible(false);
                } else {
                    api.columns(-1).visible(true);
                }
            }
        });


        function updateStatus(id, status) {
            $.ajax({
                url: "{{ url('requestItems') }}/" + id + "/status",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // Perbarui jumlah badge notifikasi
                    if (response.pendingRequestsCount > 0) {
                        $('.badge-danger').text(response.pendingRequestsCount);
                    } else {
                        $('.badge-danger').remove();
                    }
                    // Reload halaman atau perbarui datatable
                    location.reload();
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating status',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }


        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add Products');
            $('#user_id').val('{{ Auth::user()->id }}'); // Set the user ID
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('requestItems') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit Products');

                    $('#id').val(data.id);
                    $('#user_id').val(data.user_id);
                    $('#product_id').val(data.user_id);
                    $('#stock').val(data.stock);
                    $('#status').val(data.status);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('requestItems') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('requestItems') }}";
                    else url = "{{ url('requestItems') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

@endsection
