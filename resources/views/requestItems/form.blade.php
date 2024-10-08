<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog"><!-- Log on to codeastro.com for more projects! -->
      <div class="modal-content">
          <form  id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
              {{ csrf_field() }} {{ method_field('POST') }}

              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title"></h3>
              </div>

              <div class="modal-body">
                <input type="hidden" id="id" name="id">
                <div class="box-body">
                    <div class="form-group">
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                    </div>
                    <div class="form-group">
                        <label >Produk</label>
                        {!! Form::select('product_id', $product, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Product --', 'id' => 'product_id', 'required']) !!}
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="text" class="form-control" id="stock" name="stock" required>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" id="status" name="status" value="Pending" disabled>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left mb-3" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>

        </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
