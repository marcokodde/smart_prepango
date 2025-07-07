@extends('layouts.app')
@section('content')
@include('purchaseorders.sidebar')
<style>
  .table-striped>tbody>tr:nth-child(even) {
    background-color: LightCyan;
  }

  .dataTables_wrapper .dataTables_filter input {
    width: 250px;
    height: 30px;
    border: 1px solid #ccc;
    padding: 0px 0;
    padding-right: 15px;
    padding-left: 15px;
    border-radius: 5px;
    background-color: lavender !important;
  }

  select[name="sales_length"] {
      border-radius: 5px;
      background-color: lavender !important;
  }

  #sales_length {
      margin-left: 10px;
  }

  input[type=date] {
    height: 30px;
    border: 1px solid #ccc;
    padding: 0px 0;
    padding-right: 15px;
    padding-left: 15px;
    border-radius: 5px;
    background-color: lavender !important;
  }

  .select2-container--default .select2-selection--single{
    background-color: lavender;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: Lavender;
    color: #393A3B;
  }

  div.dtsp-searchPanes {
    justify-content: space-evenly !important;
  }

  table.dataTable td th {
    font-size: 90%;
  }

  th.numeric, td.numeric {
    text-align: right !important;
  }

  .select2-container--default .select2-selection--multiple {
    background-color: lavender;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: Lavender;
    color: #393A3B;
  }
</style>
<div class="col-xs-12 col-sm-12">
  <form method="POST" action="{{ route('purchaseorders.detailbywarehouse') }}">
    {{ csrf_field() }}
    <h3>Purchase Orders by Warehouse</h3>
    <div class="form-group row {{ $errors->has('warehouse_id') ? 'has-error' : '' }}">
      <div class="col-sm-4">
        <select id="warehouse_id" name="warehouse_id[]" class="form-control warehouses" multiple>
          <option></option>
          @foreach ($warehouses as $whs)
              <option value="{{$whs->id}}" @if (isset($warehouse) && $whs->id == $warehouse->id) selected @endif>{{$whs->name}}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group row {{ $errors->has('start') ? 'has-error' : '' }}">
        <div class="col-sm-4">
            <input type="date" class="form-control" id="start" name="start" placeholder="From Date" value="{{ old('start', !empty($start) ? $start : date('Y-m-d') ) }}">
        </div>
    </div>
    <div class="form-group row {{ $errors->has('end') ? 'has-error' : '' }}">
        <div class="col-sm-4">
            <input type="date" class="form-control" id="end" name="end" placeholder="To Date" value="{{ old('end',  !empty($end) ? $end : date('Y-m-d') ) }}">
        </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-4">
        <button id="getreport" type="submit" class="btn btn-success btn-sm">Generate Report</button>
      </div>
    </div>
  </form>
  @if (isset($purchaseorders) && count($purchaseorders) > 0)
      <br>
      <h4 id="purchaseorderstitle">Purchase Orders for <strong>
          @foreach ($warehouses_ids as $whs)
            {{ $whs->name }},
          @endforeach </strong>
      from {{$start}} to {{$end}}</h4>
      <table id="purchaseorders" name="purchaseorders" class="table nowrap table-hover table-condensed table-striped table-bordered" style="font-size:98%">
        <thead>
          <tr style="background-color:Lavender">
            <th>Warehouse</th>
            <th>Vendor</th>
            <th>PO #</th>
            <th>Date</th>
            <th>Status</th>
            <th>Item</th>
            <th>Use Type</th>
            <th>Rate</th>
            <th>Ordered Qty</th>
            <th>Ordered Total</th>
            <th>Received Qty</th>
            <th>Received Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchaseorders as $po)
            <tr>
              <td>{{ $po->warehouse ?? '' }}</td>
              <td>{{ $po->vendor_name ?? '' }}</td>
              <td>{{ $po->po_number ?? 0 }}</td>
              <td>{{ $po->po_date ?? ''}}</td>
              <td>{{ $po->status ?? ''}}</td>
              <td>{{ $po->item ?? ''}}</td>
              <td>{{ $po->use_type ?? ''}}</td>
              <td class="numeric">{{ $po->rate ?? ''}}</td>
              <td class="numeric">{{ $po->ordered_quantity ?? 0}}</td>
              <td class="numeric">{{ $po->ordered_total ?? 0}}</td>
              <td class="numeric">{{ $po->received_quantity ?? 0}}</td>
              <td class="numeric">{{ $po->received_total ?? 0}}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background-color:Lavender">
            <th colspan="8"></th>
            <th class="numeric"></th>
            <th class="numeric"></th>
            <th class="numeric"></th>
            <th class="numeric"></th>
          </tr>
      </tfoot>
      </table>

  @elseif (isset($warehouses_ids))
    <h4>No Purchase Orders found for
      <strong>
        @foreach ($warehouses_ids as $whs)
          {{ $whs->name }},
        @endforeach
      </strong>
      from {{$start}} to {{$end}}</h4>
  @endif
  <br>
  {{--<a id="scrolltop"></a>--}}
  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
  </div>
</div>
  <script>
    $(document).ready( function () {
      ttl = $('#purchaseorderstitle').text().replace(/\s+/g, '_').replace(/,/g, '_');
      var fn = ttl + '_' + moment().format('YYYYMMDD');
      $('#purchaseorders').DataTable( {
        fixedHeader : true,
        searchPanes: {
          columns: [ 0, 1, 4 ],
          cascadePanes: true,
          viewTotal: true,
          layout: 'columns-3'
        },
        paging: false,
        searching: true,
        language: {
          searchPlaceholder: "Search...",
          search: "_INPUT_",
          sLengthMenu: "_MENU_",
        },
        order: [[ 0, "desc" ]],
        dom: 'PBfrt',
        columnDefs: [{ searchPanes: { show: true }, targets: [ 0, 1, 4 ] }],
        buttons: {
            buttons: [
              {
                  extend: 'copyHtml5',
                  text: '<i class="fa fa-files-o"></i>',
                  titleAttr: 'Copy',
                  charset: 'utf8',
                  exportOptions: {
                      stripHtml: true
                  }
              },
              {
                  extend: 'csvHtml5',
                  text: '<i class="fa fa-file-text-o"></i>',
                  titleAttr: 'CSV',
                  charset: 'utf8',
                  filename: fn,
                  exportOptions: {
                      stripHtml: true
                  }
              },
              {
                  extend: 'excelHtml5',
                  text: '<strong><i class="fa fa-file-excel-o" /></strong>',
                  titleAttr: 'Excel',
                  filename: fn,
                  title: null,
                  autoFilter: true,
                  sheetName: 'POs by Warehouse',
                  exportOptions: {
                      stripHtml: true
                  }
              },
            ],
            dom: {
              button: {
                className: 'btn btn-primary btn-sm'
              }
            }
        },
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var intVal = function (i,) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
              orderedqty_idx = 8;
              orderedtotal_idx = 9;
              receivedqty_idx = 10;
              receivedtotal_idx = 11;
            
            orderedqty = api
              .column(orderedqty_idx, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            orderedtotal = api
              .column(orderedtotal_idx, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            receivedqty = api
              .column(receivedqty_idx, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            receivedtotal = api
              .column(receivedtotal_idx, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            $(api.column(orderedqty_idx).footer()).html(orderedqty);
            $(api.column(orderedtotal_idx).footer()).html(orderedtotal.toFixed(2));
            $(api.column(receivedqty_idx).footer()).html(receivedqty);
            $(api.column(receivedtotal_idx).footer()).html(receivedtotal.toFixed(2));
        },
      });
    });

    $(document).ready(function(){
        $('.warehouses').select2({
            closeOnSelect: true,
            allowClear: true,
            placeholder: '   Select Warehouse...'
        }).on("select2:unselecting", function(e) {
            var self = $(this);
            setTimeout(function() {
                self.select2('close');
            }, 0);
        });

    });
  </script>
@endsection