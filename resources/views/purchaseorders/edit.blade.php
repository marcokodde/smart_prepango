@extends('layouts.app')
@section('content')
@include('purchaseorders.sidebar')
<style>
    .table-striped>tbody>tr:nth-child(even) {
        background-color: LightCyan;
    }
    table { empty-cells:show; }

    .select2-container--default .select2-selection--single{
        background-color: lavender;
        height: 30px !important;
    }

    input[type=date], input[type=text] {
        height: 30px;
        border: 1px solid #ccc;
        padding: 0px 0;
        padding-right: 15px;
        padding-left: 15px;
        border-radius: 5px;
        background-color: lavender !important;
    }

    input[type=number] {
        height: 30px;
        border: 1px solid #ccc;
        padding: 0px 0;
        padding-right: 15px;
        padding-left: 15px;
        border-radius: 5px;
        background-color: lavender !important;
    }

    textarea {
        border: 1px solid #ccc;
        padding: 0px 0;
        padding-right: 15px;
        padding-left: 15px;
        border-radius: 5px;
        background-color: lavender !important;
    }
    input[type=file] {
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: lavender !important;
    }

    .select2-selection__placeholder {
        font-weight: bold;
    }  
</style>
<div class="col-xs-12 col-sm-9">
    <h2>Update Purchase Orders</b></h2>
    <h2>Date: <b>{{$po->TxnDate}}</b></h2>
    @if ($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif
    <form id="poupdate" method="POST" action="{{ route('purchaseorders.update', $po->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="form-group row {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date" class="col-sm-2 col-form-label">Date:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" name="date" id="date" value="{{ $po->TxnDate ?? date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="RefNumber" class="col-sm-2 col-form-label">PO number:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="RefNumber" id="RefNumber" value="{{ old('RefNumber', !empty($po->RefNumber) ? $po->RefNumber : NULL ) }}">
            </div>
        </div>
        <div class="form-group row {{ $errors->has('vendor') ? 'has-error' : '' }}">
            <label for="vendor" class="col-sm-2 col-form-label">Select Vendor:</label>
            <div class="col-sm-4">
                <select id="vendor" class="form-control vendor" name="vendor">
                    <option></option>
                    @foreach ($vendors as $vendr)
                        <option @if ($po->Vendor_ListID == 'mexico' && $po->VendorName == $vendr->Name) selected @elseif ( $po->Vendor_ListID != 'mexico' && $po->Vendor_ListID === $vendr->ListID) selected @endif value="{{$vendr->id}}">{{$vendr->Name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row {{ $errors->has('shipto') ? 'has-error' : '' }}">
            <label for="shipto" class="col-sm-2 col-form-label">Select Ship to:</label>
            <div class="col-sm-4">
                <select id="shipto" class="form-control shipto" name="shipto">
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
                <input type="file" class="form-control" name="invoice" id="invoice" placeholder="vendor invoice" value="">
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
                <select id="status" class="form-control status" name="status">
                    @if ($po->in_quickbooks == 'true')
                        <option selected value="true">TRUE</option>
                    @else
                        <option @if ($po->in_quickbooks == 'PENDING') selected @endif value="PENDING">PENDING - incomplete</option>
                        <option @if ($po->in_quickbooks == 'false') selected @endif value="false">Submitted to Vendor</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="closeout">Closeout PO:</label>
            <div class="col-sm-4">
                <input type="checkbox" value="{{ old('closeout', !empty($po->closeout) ? $po->closeout : 0 ) }}" @if($po->closeout==1) checked @endif id="closeout" name="closeout">
            </div>
        </div>
        <button type="submit" class="btn btn-success btn-sm" style="float:right; margin-bottom: 10px;"><i class="fa fa-save"></i> Update</button>
        <h3>Invoices</h3>
        <table class="table table-hover table-condensed table-striped table-bordered">
            <thead style="background-color:Lavender">
                <th>Invoice</th>
                <th>Uploaded By</th>
                <th>Created</th>
                <th></th>
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
                        <td colspan="3" style="text-align: center; vertical-align: middle;">No notes available</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <table id="items" name="items" class="table order-list table-hover table-condensed table-striped table-bordered">
            <thead style="background-color:Lavender">
                <th>Quantity</th>
                <th>Item</th>
                <th>Averagecost</th>
                <th></th>
            </thead>
            <tbody>
                @if(isset($po->details) && $po->details->count() > 0)
                    @foreach($po->details->sortBy('item') as $detail)
                        <tr>
                            <td><input type="number" step="1" class="form-control" name="rows[{{$detail->id}}][qty]" placeholder="" value="{{$detail->Quantity}}"></td>
                            <td>
                                <input type="hidden" name="rows[{{$detail->id}}][whs_item_id]" value="{{$detail->whs_item_id}}">
                                @if (isset($products->where('id', $detail->whs_item_id )->first()->item_sku)) {{$products->where('id', $detail->whs_item_id )->first()->item_sku}} - @endif
                                {{$detail->item}} 
                                @if($shipto->concept_id==7)
                                    @if(isset($products->where('id', $detail->whs_item_id )->first()->concept) && $products->where('id', $detail->whs_item_id )->first()->concept==="LEGO")
                                        @if(isset($products->where('id', $detail->whs_item_id )->first()->item_code)) - Mixed Use @else - Retail Only @endif 
                                    @else
                                        - <strong><font color="#ba1a0f">ITEM MISSING CONCEPT</font></strong> @if(isset($products->where('id', $detail->whs_item_id )->first()->item_code)) - Mixed Use @else - Retail Only @endif
                                    @endif
                                @endif
                            </td>
                            <td><input type="number" step="0.01" class="form-control required rateval" name="rows[{{$detail->id}}][rte]" value="{{$detail->Rate}}"></td>
                            <td>Set QTY to <strong>0</strong> to remove</td>
                        </tr>
                    @endforeach
                @elseif (isset($reorders) && $reorders->count() > 0)
                    @foreach ($reorders as $detail)
                        <tr>
                            <td>
                                <input type="number" step="1" class="form-control" name="newrows[{{ $detail->vendor_item_id ?? 0 }}][qty]" placeholder="" value="{{ $detail->reorder_qty }}">
                            </td>
                            <td>
                                <input type="hidden" name="newrows[{{$detail->vendor_item_id}}][whs_item_id]" value="{{$detail->whs_item_id}}">
                                @if (isset($products->where('id', $detail->whs_item_id )->first()->item_sku)) {{$products->where('id', $detail->whs_item_id )->first()->item_sku}} - @endif
                                {{$detail->item}} 
                                @if($shipto->concept_id==7)
                                    @if(isset($products->where('id', $detail->whs_item_id )->first()->concept) && $products->where('id', $detail->whs_item_id )->first()->concept==="LEGO")
                                        @if(isset($products->where('id', $detail->whs_item_id )->first()->item_code)) - Mixed Use @else - Retail Only @endif 
                                    @else
                                        - <strong><font color="#ba1a0f">ITEM MISSING CONCEPT</font></strong> @if(isset($products->where('id', $detail->whs_item_id )->first()->item_code)) - Mixed Use @else - Retail Only @endif
                                    @endif
                                @endif
                            </td>
                            <td>
                                <input type="number" step="0.01" class="form-control required rateval" name="newrows[{{$detail->vendor_item_id ?? 0}}][rte]" value="{{ $detail->rate }}">
                            </td>
                            <td>Set QTY to <strong>0</strong> to remove</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <button type="button" class="btn btn-warning btn-sm" id="addrow" style="margin-top: 10px;"><i class="fa fa-plus-circle"></i> Add Item</button>
        <button type="submit" class="btn btn-success btn-sm" style="float:right; margin-top: 10px;"><i class="fa fa-save"></i> Update</button>
    </form>
    @if ($po->shipto->concept->name === 'LEGO')
        <form id="form_file" method="POST" action="{{ route('purchaseorders.update_csv', $po->id) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
                <div class="form-group row input-group upload">
                    <h3>Import Items from CSV - Will Replace all currently assign Products</h3>
                </div>
                <div class="col-sm-5 form-group row input-group upload">
                    <input type="hidden" id="[po_id]" name="concept_id" value="{{$po->id}}">
                    <input type="file" accept=".csv" class="filestyle" id="csvfile" name="csvfile">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary upload"><i class="fa fa-upload"></i> Upload</button>
                    </span>
                </div>
        </form>
    @endif
    <br>
</div>
<script>
    $(document).ready(function () {
        $('.vendor').select2({placeholder: '   Select Vendor'});
        $('.shipto').select2({placeholder: '   Select Ship To'});
        $('.status').select2({placeholder: '   Select Status'});
        $('.products').select2({placeholder: '   Select Product'});

        $('.pconcept').change(function() {
            var old_listid = $('#item_code').val();
            var listid = $('#ListID').val();
            if ($(this).val() == 38 || $(this).val() == 40) {
                $('#ListID').val($('#item_code').val());
            } else {
                $('#ListID').val(listid);
            }
        });

        if($('#status :selected').text() == "TRUE"){
            $('#status').attr('disabled', 'disabled'); 
        }

        $(":checkbox").change(function(){
            $(this).val($(this).is(":checked") ? 1 : 0);
        });

        $('#poupdate').on('submit', function(e) {
            $('#status').removeAttr('disabled');
            $('input.required').each(function() {
                if($.trim($(this).val()).length == 0){
                    $(this).focus();
                    $.alert({
                        title: 'Encountered an error!',
                        content: 'The <strong>Rate</strong> amount is required.<br>Plese fill out required fields.',
                        type: 'orange',
                        buttons: {
                            closeAction: {
                                text: '<i class="fa fa-close"></i>',
                                btnClass: 'btn-default',
                                action: function () {
                                    //close
                                }
                            }
                        },
                    });
                    e.preventDefault();
                    return false;
                }
            });
        });

        $('#pos').DataTable( {
            "order": [[ 1, "desc" ]]
        });
        var counter_row = 0;

        $('#items').DataTable( {
            dom:"t",
            ordering: true,
            bSort: true,
            paging: false,
            fixedHeader: true,
        });

        $("#addrow").on("click", function () {
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td><input type="number" step="1" class="form-control qinput" name="newrows[' + counter_row +'][qty]"></td>';
            cols += '<td><select class="form-control newproducts" name="newrows[' + counter_row +'][whs_item_id]" style="width: 100%; height: 30px; padding: 0px 0; padding-right: 15px; padding-left: 15px; text-align: left; display:flex; align-items: center; justify-content: space-between; margin-bottom: 5px;"><option></option>';
            @foreach ($products as $product)
                cols += '<option data-rate="{{$product->AverageCost}}" value="{{$product->id}}"> {{$product->id}} - {{$product->item}} - {{strtoupper($product->status)}} @if($product->concept_id==7) - {{strtoupper($product->use_type)}} @endif</option>';
            @endforeach
            cols += '</select></td>';
            cols += '<td><input type="number" step="0.01" class="form-control required rateval" name="newrows[' + counter_row +'][rte]"></td>';
            cols += '<td style="text-align: center"><button type="button" class="ibtnDel btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>';

            newRow.append(cols);
            $('table.order-list').append(newRow);

            $('.newproducts').select2({
                placeholder: '   Select Product'
            }).on('select2:select', function(e) {
                var data = e.params.data;
                $('input[name="newrows[' + (counter_row - 1) +'][rte]"]').val(data.element.attributes[0].nodeValue);
            });
            counter_row++;
        });

        $("table.order-list").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();
            counter_row -= 1
        });
    });
    </script>
@endsection