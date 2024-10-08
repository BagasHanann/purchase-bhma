<!-- resources/views/order/action_buttons.blade.php -->

<a onclick="editForm({{ $orderItem->id }})" class="btn btn-primary btn-xs">
  <i class="glyphicon glyphicon-edit"></i> Edit
</a>
<a onclick="deleteData({{ $orderItem->id }})" class="btn btn-danger btn-xs">
  <i class="glyphicon glyphicon-trash"></i> Delete
</a>
<a href="{{ route('exportPDF.orderItem', $orderItem->id) }}" class="btn btn-warning btn-xs">
  <i class="fa fa-file-pdf-o"></i> PDF
</a>