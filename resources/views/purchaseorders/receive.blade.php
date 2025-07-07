<div>
    <div class="row">
<style>
  .table-striped>tbody>tr:nth-child(even) {
    background-color: LightCyan;
  }
  tr[data-complete="true"] {
  opacity: 0.6;
  pointer-events: none;
  background-color: #B2D2F5FF;
  }
</style>
<div class="col-xs-12 col-sm-9">
    <h2>Update Purchase Orders </b></h2>
    <h2>Date: <b>{{$po->TxnDate}}</b></h2>

    @if($errors->any())
    {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
    @endif
    <form method="POST" action="{{ route('purchaseorders.receive', $po->id) }}">
        {{ csrf_field() }}
        {{ method_field('POST') }}

            <button type="button" class="btn btn-success" style="float:right"><i class="fa fa-save"></i>Receive</button>

            <table class="table table-hover table-condensed table-striped table-bordered">
              <tbody>
                <tr>
                  <td><b>ID:</b></td><td><b>{{$po->id}}</b></td>
                </tr>
                <tr>
                  <td><b>Date:</b></td><td><b>{{$po['TxnDate']}}</b></td>
                </tr>
                <tr>
                  <td><b>Vendor:</b></td><td>{{$po['VendorName']}}</td>
                </tr>
                <tr>
                  <td><b>Ship to:</b></td><td>{{$po['ShipToName']}}</td>
                </tr>
                <tr>
                  <td><b>RefNumber:</b></td><td>{{$po['RefNumber']}}</td>
                </tr>
                <tr>
                  <td><b>In Quickbooks:</b></td><td>{{$po->in_quickbooks ?? ''}} </td>
                </tr>
                <tr>
                  <td><b>Date Created:</b></td><td>{{$po['created_at']}}</td>
                </tr>
                <tr>
                  <td><b>Date Updated:</b></td><td>{{$po['updated_at']}}</td>
                </tr>
              </tbody>
            </table>

              <h3>Notes</h3>
              <table class="table table-hover table-condensed table-striped table-bordered">
                <thead style="background-color:Lavender">
                  <th>Note</th>
                  <th>Created by</th>
                  <th>Created</th>
                </thead>
                <tbody>
                  @if (isset($po->notes) && count($po->notes)>0)
                    @foreach ($po->notes as $note)
                      <tr>
                        <td><textarea disabled style="border: none; width: 100%">{{$note->note}}</textarea></td><td>{{$note->user->name ?? ''}}</td><td>{{$note->created_at}}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <tr>
                        <td colspan="3" style="text-align: center; vertical-align: middle;">No notes available</td>
                      </tr>
                    </tr>
                  @endif
                </tbody>
              </table>

              <br>
              <div class="form-group">
                <label for="barcode_input">Scan Barcode:</label>

                <p class="help-block">Scan the barcode of the product to receive it. Press Enter to submit.</p>
                <input type="text" class="form-control" id="barcode_input" onkeypress="return event.key !== 'Enter';">
              </div>

          <table id="products_table" class="table order-list table-hover table-condensed table-striped table-bordered">
            <thead>
                <tr style="background-color:Lavender" >
                    <th>Quantity Ordered</th>
                    <th>Quantity Received</th>
                    <th>Date Received</th>
                    <th>Description</th>
                    <th>Rate</th>
                    <th>Scanned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->details->sortBy('item') as $item)
                  @if ($loop->first)
                    <tr data-barcode="7501007528786" style="background-color: LightCyan;" data-expected="{{ $item->Quantity }}" data-complete="false">
                      <td>{{$item->Quantity}}</td>

                      <td {{ $errors->has('rows.'.$item->id.'.received_quantity') ? "class=has-error" : '' }}>
                        <input class="form-control manualQtyBtn" id="manualQtyBtn" name="rows[{{$item->id}}][received_quantity]" placeholder=""
                          value="{{ old('rows.'.$item->id.'.received_quantity', $item->received_quantity ?? '') }}"
                          data-product-id="{{ $item->id }}">
                      </td>

                      <td {{ $errors->has('rows.'.$item->id.'.received') ? "class=has-error" : '' }}>
                        <input type="date" class="form-control" name="rows[{{$item->id}}][received]" placeholder=""
                        value={{ old( 'rows.'.$item->id.'.received', !empty($item->received) ? $item->received : '' ) }}>
                      </td>

                      @if (isset($item->whs_item->products[0]->item_sku) && $item->whs_item->products[0]->item_sku != '')
                        <td>{{$item->whs_item->products[0]->item_sku ?? ''}} - {{$item->item}}</td>
                      @else
                        <td>{{$item->item}}</td>
                      @endif

                      <td>{{$item->Rate}}</td>
                      <td class="scanned-count">0</td>
                    </tr>
                  @else

                    <tr data-barcode="{{ $item->id }}" data-expected="{{ $item->Quantity }}" data-complete="false">
                        <td>{{$item->Quantity}}</td>

                        <td {{ $errors->has('rows.'.$item->id.'.received_quantity') ? "class=has-error" : '' }}>
                          <input class="form-control manualQtyBtn" id="manualQtyBtn"  name="rows[{{$item->id}}][received_quantity]" placeholder=""
                            value="{{ old('rows.'.$item->id.'.received_quantity', $item->received_quantity ?? '') }}"
                            data-product-id="{{ $item->id }}">
                        </td>
                        <td {{ $errors->has('rows.'.$item->id.'.received') ? "class=has-error" : '' }}>
                          <input type="date" class="form-control" name="rows[{{$item->id}}][received]" placeholder="" value={{ old( 'rows.'.$item->id.'.received', !empty($item->received) ? $item->received : '' ) }}>
                        </td>
                        @if (isset($item->whs_item->products[0]->item_sku) && $item->whs_item->products[0]->item_sku != '')
                        <td>{{$item->whs_item->products[0]->item_sku ?? ''}} - {{$item->item}}</td>
                        @else
                        <td>{{$item->item}}</td>
                        @endif
                        <td>{{$item->Rate}}</td>
                        <td class="scanned-count">0</td>
                    </tr>
                  @endif
                @endforeach
            </tbody>
        </table>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#pos').DataTable({
            "order": [[ 1, "desc" ]]
        });

        const input = document.getElementById('barcode_input');
          input.addEventListener('keypress', function (e) {
            if (e.key == 'Enter') {
              e.preventDefault();
              const code = input.value.trim();
              input.value = '';
              const row = document.querySelector(`#products_table tr[data-barcode="${code}"]`);

                if (row) {

                  if (row.dataset.complete === 'true') {
                    alert('The row in the product complete, succesfuly.');
                    return;
                  }

                  const scannedCell = row.querySelector('.scanned-count');
                  let count = parseInt(scannedCell.textContent, 10);
                  row.style.backgroundColor = '#d4edda';
                  let scanned = parseInt(scannedCell.textContent, 10);
                  const expected = parseInt(row.dataset.expected, 10);

                  if (scanned == expected) {
                    row.dataset.complete = 'true';
                    row.style.backgroundColor = '#A3CAF3FF';
                  }

                  if (scanned < expected) {
                      scannedCell.textContent = scanned + 1;

                      const receivedInput = row.querySelector('input[name^="rows"][name$="[received_quantity]"]');
                      if (receivedInput) {
                        let receivedQuantity = parseInt(receivedInput.value, 10) || 0;
                        receivedInput.value = receivedQuantity + 1;
                      }
                      if (scanned == expected) {
                        row.dataset.complete = 'true';
                        row.style.backgroundColor = '#A3CAF3FF';
                      }
                  } else {
                      alert('¡Quantity max to row!');
                  }

                  const receivedDateInput = row.querySelector('input[name^="rows"][name$="[received]"]');
                    if (receivedDateInput) {
                        const today = new Date().toISOString().split('T')[0];
                        receivedDateInput.value = today;
                    }

                  row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                  row.classList.add('highlight');
                  setTimeout(() => {
                    row.classList.remove('highlight');
                  }, 2000);

                } else {
                  alert('Códe not found in the Purchase Order');
                }
            }
          });


          $('#products_table').on('change', '.manualQtyBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const input = $(this);
            var tr = $(this).closest('tr');
            var product_name = tr.find('td:first').text();
            var current_qty = tr.find('.addQuantity').val();
            const barcode = $(this).val();
            const productId = $(this).data('product-id');

            $.confirm({
              columnClass: 'col-md-4 col-md-offset-2',
              escapeKey: true,
              title: 'Manual quantity: <b>' + product_name + '</b>',
              content: function () {
                return '' +'<form action="" class="manualQtyForm">' +
                  '<div class="form-group">' +
                    '<label>Bare code</label>' +
                    '<input type="text" class="form-control" value="' + barcode + '" readonly>' +
                    '</div>' +
                  '<div class="form-group">' +
                    '<label>Quantity:</label>' +
                    '<input type="number" min="0" class="form-control inputQty" value="' + current_qty + '" required>' +
                    '<input type="hidden" class="productId" value="' + productId + '">' +
                    '</div>' +
                  '</form>';
              },
              buttons: {
                confirmar: {
                  text: '<i class="fa fa-check"></i>',
                  btnClass: 'btn-blue',
                    action: function () {
                      var qty = this.$content.find('.inputQty').val();
                      const productId = this.$content.find('.productId').val();
                      if (qty < 0) {
                        $.alert('Please enter a valid quantity.');
                        return false;
                      }
                      input.val('');
                      input.val(qty);
                      const tr = input.closest('tr');
                      tr.find('.scanned-count').text(qty);
                  }
                },
                cancelar: {
                  text: '<i class="fa fa-times"></i>',
                  btnClass: 'btn-red',
                }
              }
            });
          });
      });
</script>
    </div>
</div>
