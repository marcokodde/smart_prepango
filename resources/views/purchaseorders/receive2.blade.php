@extends('layouts.app')
@section('content')
@include('purchaseorders.sidebar')
<style>
  .table-striped>tbody>tr:nth-child(even) {
    background-color: LightCyan;
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

            <button type="submit" class="btn btn-success" style="float:right"><i class="fa fa-save"></i>Receive</button>
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
        <table class="table order-list table-hover table-condensed table-striped table-bordered">
            <thead>
                <tr style="background-color:Lavender">
                    <th>Quantity Ordered</th>
                    <th>Quantity Received</th>
                    <th>Date Received</th>
                    <th>Description</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->details->sortBy('item') as $item)
                <tr>
                    <td>{{$item->Quantity}}</td>

                    <td {{ $errors->has('rows.'.$item->id.'.received_quantity') ? "class=has-error" : '' }}>
                      <input  class="form-control" name="rows[{{$item->id}}][received_quantity]" placeholder="" value={{ old( 'rows.'.$item->id.'.received_quantity', !empty($item->received_quantity) ? $item->received_quantity : '' ) }}>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>
<script>

    $(document).ready(function () {
        $('#pos').DataTable( {
            "order": [[ 1, "desc" ]]
        });

    
    
    });
    
    </script>


@endsection