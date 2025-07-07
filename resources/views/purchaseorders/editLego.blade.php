@extends('layouts.app')
@section('content')
@include('purchaseorders.sidebar')
<style>
  .table-striped>tbody>tr:nth-child(even) {
    background-color: LightCyan;
  }
</style>
<div class="col-xs-12 col-sm-9">
    <h2>Update Purchase Orders</b></h2>
    <h2>Date: <b>{{$po->TxnDate}}</b></h2>
    @if ($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif
    <form method="POST" action="{{ route('purchaseorders.update', $po->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="form-group row {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date" class="col-sm-2 col-form-label">Date:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" name="date" value="{{ $po->TxnDate ?? date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="RefNumber" class="col-sm-2 col-form-label">PO number:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="RefNumber"  value="{{ old('RefNumber', !empty($po->RefNumber) ? $po->RefNumber : NULL ) }}">
            </div>
        </div> 
        <div class="form-group row {{ $errors->has('vendor') ? 'has-error' : '' }}">
            <label for="vendor" class="col-sm-2 col-form-label">Select Vendor:</label>
            <div class="col-sm-4">
                <select id="vendor" class="form-control" name="vendor">
                    <option></option>
                    @foreach ($vendors as $vendor)
                    <option @if ($po->Vendor_ListID == $vendor->ListID) selected @endif value="{{$vendor->id}}">{{$vendor->Name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row {{ $errors->has('shipto') ? 'has-error' : '' }}">
            <label for="shipto" class="col-sm-2 col-form-label">Select Ship to:</label>
            <div class="col-sm-4">
                <select id="shipto" class="form-control" name="shipto">
                    <option></option>
                    @foreach ($shiptos as $site)
                    <option @if ($po->ShipToID == $site->id) selected @endif value="{{$site->id}}">{{$site->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="payment_terms" class="col-sm-2 col-form-label">Payment Terms:</label>
            <div class="col-sm-4">
                <textarea name="payment_terms" id="payment_terms" rows="6" style="width:100%;">{{ old('payment_terms', NULL ) }}</textarea>
            </div>
        </div>

        <div class="form-group row {{ $errors->has('payment_due_date') ? 'has-error' : '' }}">
            <label for="payment_due_date" class="col-sm-2 col-form-label">Payment Due Date:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" name="payment_due_date" id="payment_due_date" value="{{ $po->payment_due_date ?? '' }}">
            </div>
        </div>        

        <div class="form-group row {{ $errors->has('invoice') ? 'has-error' : '' }}">
            <label for="invoice" class="col-sm-2 col-form-label">Add invoice:</label>
            <div class="col-sm-4">
              <input type="file" class="form-control" name="invoice" placeholder="vendor invoice" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="notes" class="col-sm-2 col-form-label">New Note:</label>
            <div class="col-sm-4">
                <textarea name="notes" id="notes" rows="6" style="width:100%;">{{ old('notes', NULL ) }}</textarea>
            </div>
        </div>
        <div class="form-group row {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status" class="col-sm-2 col-form-label">PO Status:</label>
            <div class="col-sm-4">
                <select id="status" class="form-control" name="status">
                    @if ($po->in_quickbooks == 'true')
                        <option selected value="true">TRUE</option>
                    @else
                        <option @if ($po->in_quickbooks == 'PENDING') selected @endif value="PENDING">PENDING - incomplete</option>
                        <option @if ($po->in_quickbooks == 'false') selected @endif value="false">Submitted to Vendor</option>
                    @endif
                </select>
            </div>
        </div>
    <h3>Invoices</h3>
    <table class="table table-hover table-condensed table-striped table-bordered">
      <thead>
        <th>Invoice</th>
        <th>Uploaded By</th>
        <th>Created</th>
      </thead>
      <tbody>
        @if (isset($po->invoices))
        @foreach ($po->invoices as $invoice)
        <tr>
          <td><a href="{{ route( 'get.po_invoice.image', $invoice->invoice) }}" download="{{$invoice->orig_filename}}"><i class="fa fa-download"></i> {{$invoice->orig_filename}}</a></td><td>{{$invoice->user->name ?? ''}}</td><td>{{$invoice->created_at}}</td>
        </tr>              
        @endforeach
        @else
        <tr><td></td><td></td><td></td></tr>
        @endif
      </tbody>
    </table>
    <h3>Notes</h3>
    <table class="table table-hover table-condensed table-striped table-bordered">
      <thead>
        <th>Note</th>
        <th>Created by</th>
        <th>Created</th>
      </thead>
      <tbody>
        @if (isset($po->notes))
        @foreach ($po->notes as $note)
        <tr>
            <td><textarea disabled style="border: none; width: 100%">{{$note->note}}</textarea></td><td>{{$note->user->name ?? ''}}</td><td>{{$note->created_at}}</td>
        </tr>              
        @endforeach
        @else
        <tr><td></td><td></td><td></td></tr>
        @endif
      </tbody>
    </table>

        <div>
            <button type="submit" class="btn btn-success" style="float:right"><i class="fa fa-save"></i> Update</button>
        </div>
        <table class="table order-list table-hover table-condensed table-striped table-bordered">
            <thead>
                <tr style="background-color:Lavender">
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Rate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($po->details as $detail)
                <tr>
                    <td><input type="number" class="form-control" name="rows[{{$detail->id}}][qty]" placeholder="" value="{{$detail->Quantity}}"></td>
                    <td><input type="hidden" name="rows[{{$detail->id}}][ListID]" value="{{$detail->Item_ListID}}">{{$detail->item}}</td>
                    <td><input text class="form-control" name="rows[{{$detail->id}}][rte]" value="{{$detail->Rate}}"></td>
                    <td>Set QTY to 0 to remove</td>
                </tr>
                @endforeach
                {{-- <tr style="background-color : gray;"><td></td><td></td><td></td><td></td></tr>
                @foreach ($products as $product)
                <tr>
                    <td>{{dd($product)}}</td>
                    <td><input type="hidden" name="newrows[{{$product->ListID}}][ListID]" value="{{$product->ListID}}">{{$product->Name}}</td>
                    <td><input type="number" class="form-control" name="newrows[{{$product->ListID}}][qty]" placeholder="" value="{{$product->Quantity}}"></td>
                    <td><input text class="form-control" name="newrows[{{$product->ListID}}][rte]" value="{{$product->Rate}}"></td>
                    <td></td>
                </tr>
                @endforeach --}}
            </tbody>
            {{-- <tbody>
                @foreach($po->details->sortBy('item') as $item)
                <tr>
                    <td><input type="number" class="form-control" name="rows[{{$item->id}}][qty]" placeholder="" value="{{$item->Quantity}}"></td>
                    <td><select class="form-control" name="rows[{{$item->id}}][whs_item_id]" style="width: 500px; height: 30px; padding: 0px 0; padding-right: 15px; padding-left: 15px; text-align: left; display:flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                        @foreach ($products as $product)
                        <option @if ($product->id == $item->whs_item_id) selected @endif value="{{$product->id}}"> {{$product->id}} - {{$product->item}} - {{$product->status}} </option>';
                        @endforeach
                        </select>
                    </td>
                    <td><input text class="form-control" name="rows[{{$item->id}}][rte]" value="{{$item->Rate}}"></td>
                    <td>Set QTY to 0 to remove</td>
                </tr>
                @endforeach
            </tbody> --}}
        </table>
        <button type="button" class="btn btn-warning" id="addrow" class="btn btn-warning" style="margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> Add Item </button>
    </form>
</div>
<script>

    $(document).ready(function () {
        $('#pos').DataTable( {
            "order": [[ 1, "desc" ]]
        });
        var counter_row = 0;
    
        $("#addrow").on("click", function () {
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td><input type="number" class="form-control" name="newrows[' + counter_row +'][qty]" placeholder="0"></td>';
            cols += '<td><select id="product_id" class="form-control product_id" name="newrows[' + counter_row +'][ListID]" style="width: 500px; height: 30px; padding: 0px 0; padding-right: 15px; padding-left: 15px; text-align: left; display:flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">';
    
            @foreach ($products as $product)
            cols += '<option value="{{$product->ListID}}"> {{$product->ManufacturerPartNumber}} {{$product->Name}} </option>';
            @endforeach
            cols += '</select></td>';
            cols += '<td><input type="text" class="form-control" name="newrows[' + counter_row +'][rte]" placeholder="$0.00"></td>';
            cols += '<td><button type="button" class="ibtnDel btn btn-danger"><i class="fa fa-delete"></i>Delete Item</button></td>';
    
    
            newRow.append(cols);
            $("table.order-list").append(newRow);
            counter_row++;
        });
   
        $("table.order-list").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
            counter_row -= 1
        });
    
    
    });
    
    </script>
@endsection