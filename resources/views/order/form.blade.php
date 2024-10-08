<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">Tambah Pembelian</h3>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="box-body">
                        <div class="form-group mb-3">
                            <label for="request_id">Kode Permintaan</label>
                            <select class="form-control" id="request_id" name="request_id" required>
                                <option value="">-- Pilih Permintaan --</option>
                                @foreach($approvedRequests as $requestId => $requestName)
                                    <option value="{{ $requestId }}">{{ $requestName }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="product_id" class="col-sm-4 col-form-label">Produk</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="product_name" readonly>
                                <input type="hidden" name="product_id" id="product_id">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="stock" class="col-sm-4 col-form-label">Qty</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="stock" name="stock" readonly>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="price" class="col-sm-4 col-form-label">Harga</label>
                            <div class="col-sm-8">
                                <input type="number" name="price" class="form-control" id="price" value="" readonly>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="total_price" class="col-sm-4 col-form-label">Total Harga</label>
                            <div class="col-sm-8">
                                <input type="text" id="total_price" name="total_price" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="supplier_id">Supplier</label>
                            {!! Form::select('supplier_id', $supplier, null, ['class' => 'form-control', 'placeholder' => '-- Choose Supplier --', 'id' => 'supplier_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date">Tanggal</label>
                            <input data-date-format='yyyy-mm-dd' type="text" class="form-control datepicker" id="date" name="date" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    // Fungsi untuk menghitung harga total secara dinamis
    $(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    function hitungTotalHarga() {
        var stock = parseFloat($('#stock').val());
        var price = parseFloat($('#price').val());

        if (!isNaN(stock) && !isNaN(price)) {
            var total_price = stock * price;
            var formatted_total_price = total_price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
            $('#total_price').val(formatted_total_price);
        }
    }

    $('#stock, #price').on('input', function() {
        hitungTotalHarga();
    });

    $('#request_id').change(function() {
    var requestId = $(this).val(); // Mengambil nilai dari dropdown saat dipilih
    if (requestId) {
        $.ajax({
            url: '{{ route("fetchApprovedRequestDetails") }}',
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'request_id': requestId  // Mengirim nilai request_id ke controller
            },
            success: function(data) {
                $('#product_id').val(data.product.id);
                $('#product_name').val(data.product.name);
                $('#price').val(data.price);  // Menetapkan nilai harga
                $('#stock').val(data.stock);  // Menetapkan nilai stok
                hitungTotalHarga();  // Menghitung total harga berdasarkan stok dan harga
            },
            error: function(xhr, status, error) {
                console.error('Error fetching approved request details');
            }
        });
    } else {
        // Reset nilai form jika request_id kosong
        $('#product_id').val('');
        $('#product_name').val('');
            $('#price').val('');
            $('#stock').val('');
            $('#total_price').val('');
        }
    });
});

</script>
