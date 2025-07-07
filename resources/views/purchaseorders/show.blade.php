@extends('layouts.app') 
@section('content')
@include('purchaseorders.sidebar')
<style>
  .table-striped>tbody>tr:nth-child(even) {
    background-color: LightCyan;
  }
</style>
<div class="col-xs-12 col-sm-9">
  <table style="width:100%">
    <h2>Purchase Order Details</b></h2>
    <tr>
      <td style="padding-bottom:10px;">
        <h2>Date: <b>{{$po->TxnDate ?? ''}}</b>
            @perm('edit_po')
              <a class="hidepo btn btn-danger btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-trash"></i> Delete</a>
              <a href="{{action('PurchaseOrderController@edit', $po->id)}}" class="btn btn-warning btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-edit"></i>Update</a>
              <a href="{{action('PurchaseOrderController@receivePurchaseOrder', $po->id)}}" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-edit"></i>Receive</a>
            @endperm
        </h2>
      </td>
    </tr>
    <tr>
      <table class="table table-hover table-condensed table-striped table-bordered">
        <tbody>
          <tr>
            <th style="background-color:Lavender"><b>ID:</b></th><td><b>{{$po->id}}</b></td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Date:</b></th><td><b>{{$po['TxnDate']}}</b></td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Vendor:</b></th><td>{{$po['VendorName']}}</td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Ship to:</b></th><td>{{$po['ShipToName']}}</td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>RefNumber:</b></th><td>{{$po['RefNumber']}}</td>
          </tr>

          <tr>
            <th style="background-color:Lavender"><b>Payment Terms:</b></th><td><textarea disabled style="border: none; width: 100%">{{$po['payment_terms']}}</textarea></td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Payment Due Date:</b></th><td><b>{{ $po['payment_due_date'] ?? '' }}</b></td>
          </tr>

          <tr>
            <th style="background-color:Lavender"><b>In Quickbooks:</b></th><td><b>{{strtoupper($po->in_quickbooks) ?? ''}}</b></td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Closeout:</b></th><td><b>{{($po->in_quickbooks==0) ? 'NO' : 'YES'}}</b></td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Created By:</b></th><td>{{$po->created_by->name ?? ''}}</td>
          </tr>                   
          <tr>
            <th style="background-color:Lavender"><b>Date Created:</b></th><td>{{$po['created_at']}}</td>
          </tr>
          <tr>
            <th style="background-color:Lavender"><b>Date Updated:</b></th><td>{{$po['updated_at']}}</td>
          </tr>
        </tbody>
      </table>
      <h3>Invoices</h3>
      <table class="table table-hover table-condensed table-striped table-bordered">
        <thead style="background-color:Lavender">
          <th>Invoice</th>
          <th>Uploaded By</th>
          <th>Created</th>
        </thead>
        <tbody>
          @if (isset($po->invoices) && count($po->invoices)>0)
            @foreach ($po->invoices as $invoice)
              <tr>
                <td><a href="{{ route( 'get.po_invoice.image', $invoice->invoice) }}" download="{{$invoice->orig_filename}}"><i class="fa fa-download"></i> {{$invoice->orig_filename}}</a></td><td>{{$invoice->user->name ?? ''}}</td><td>{{$invoice->created_at}}</td>
              </tr>              
            @endforeach
          @else
            <tr>
              <td colspan="3" style="text-align: center; vertical-align: middle;">No invoices available</td>
            </tr>
          @endif
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
      
      <h3>Packing List items</h3>
      <table id="po_detail" name="po_detail" class="table table-hover table-condensed table-sorting table-striped table-bordered">
        <thead>
          <tr style="background-color:Lavender">
            <th>Quantity Ordered</th>
            <th>Quantity Received</th>
            <th>Date Received</th>
            <th>Received By</th>
            <th>Description</th>
            @if ($po->shipto->concept_id == 7)
              <th>Use</th>
            @endif
            <th>Rate</th>
          </tr>
        </thead>
        <tbody>
          @foreach($po->details->sortBy('item') as $item)
          @if ($item->Quantity > 0 )        
          <tr>
            <td>{{$item->Quantity}}</td>
            <td>{{$item->received_quantity}}</td>
            <td>{{$item->received}}</td>
            <td>{{$item->user->name ?? ''}}</td>
            @if (isset($item->whs_item->products[0]->item_sku) && $item->whs_item->products[0]->item_sku != '')
            <td>{{$item->whs_item->products[0]->item_sku ?? ''}} - {{$item->item}}</td>
            @else
            <td>{{$item->item}}</td>
            @endif
            @if ($po->shipto->concept_id == 7)
              <td>{{$item->lego_use()}}</td>
            @endif
            <td>{{$item->Rate}}</td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </tr>
  </table>
  <br>
</div>
<script type="text/javascript">
$(document).ready(function(){

  $('#po_detail').DataTable( {
      dom:"t",
      ordering: true,
      bSort: true,
      paging: false,
      fixedHeader: true,
  });

  $('.hidepo').on('click', function(){
    id="{{$po['id']}}";
    okdelmsg="po <strong>{{$po['id']}}</strong> marked as deleted."
    $.confirm({
      escapeKey: true,  
      icon: 'fa fa-warning',
      title: 'Confirm',
      content: 'Are you sure you want to delete?',
      closeIcon: true,
      closeIconClass: 'fa fa-close',
      buttons: {
        yes: {
            keys: ['y'],
            btnClass: 'btn-success',
            action: function () {
              var working=$.confirm({
                  lazyOpen: true,
                  icon: 'fa fa-spinner fa-spin',
                  title: 'Working!',
                  content: 'Sit back, we are processing your request!'
              });
              working.open();
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: "{{ route('purchaseorders.destroy', ['purchaseorder' => $po->id]) }}",
                  method: 'delete',
                  success: function(data) {
                    working.close();
                    $.alert({
                        icon: 'fa fa-check',
                        title: 'Success!',
                        content: okdelmsg,
                    });
                    window.location = "{{route('purchaseorders.index')}}";
                  }
              });
            }
        },
        no: {
            keys: ['N'],
            btnClass: 'btn-danger',
            action: function () {
            }
        },
      }
    });
  });
});  
</script>
@endsection