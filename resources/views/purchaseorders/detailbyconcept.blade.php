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

#scrolltop {
  display: inline-block;
  background-color: #FF9800;
  width: 30px;
  height: 30px;
  text-align: center; margin: auto;
  border-radius: 50px;
  position: fixed;
  bottom: 30px;
  right: 30px;
  transition: background-color .3s, 
    opacity .5s, visibility .5s;
  opacity: 0;
  visibility: hidden;
  z-index: 1000;
}
#scrolltop::after {
  content: "\f077";
  font-family: FontAwesome;
  font-weight: normal;
  font-style: normal;
  font-size: 1.6em;
  line-height: 26px;
  color: #fff;
}
#scrolltop:hover {
  cursor: pointer;
  background-color: #333;
  text-decoration: none !important;
}
#scrolltop:active {
  background-color: #555;
}
#scrolltop.show {
  opacity: 1;
  visibility: visible;
}

.card-stats .card-header.card-header-icon, .card-stats .card-header.card-header-text {
    text-align: right;
}
  .card {
    border: 0;
    margin-bottom: 30px;
    margin-top: 30px;
    border-radius: 6px;
    color: #333;
    background: #fff;
    width: 100%;
    box-shadow: 0 1px 4px 0 rgb(0 0 0 / 14%);
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
  }
  .card:hover{
    transform: scale(1.01);
    box-shadow: 0 5px 10px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
  }
    .card-stats .card-header.card-header-icon i {
      font-size: 36px;
      line-height: 56px;
      width: 56px;
      height: 56px;
      text-align: center;
  }
  .card [class*=card-header-] {
    margin: 0 15px;
    padding: 0;
    position: relative;
}
.card .card-header {
    border-bottom: none;
    background: transparent;
}
  .card .card-footer {
    display: flex;
    align-items: center;
    background-color: transparent;
    border: 0;
}
  .card-stats .card-header+.card-footer {
    border-top: 1px solid #eee;
    margin-top: 20px;
}
  .card [class*=card-header-] .card-icon, .card [class*=card-header-] .card-text {
      border-radius: 3px;
      background-color: #999;
      padding: 15px;
      margin-top: -20px;
      margin-right: 15px;
      float: left;
  }
.card .card-header-danger .card-icon, .card .card-header-danger .card-text, .card .card-header-danger:not(.card-header-icon):not(.card-header-text) {
      box-shadow: 0 4px 20px 0 rgb(0 0 0 / 14%), 0 7px 10px -5px rgb(255 152 0 / 40%);
  }
  .card.bg-danger, .card .card-header-danger .card-icon, .card .card-header-danger .card-text, .card .card-header-danger:not(.card-header-icon):not(.card-header-text), .card.card-rotate.bg-danger .back, .card.card-rotate.bg-danger .front {
      background: linear-gradient(
  60deg
  ,#FF3030,#FB0000);
  }
.card .card-header-success .card-icon, .card .card-header-success .card-text, .card .card-header-success:not(.card-header-icon):not(.card-header-text) {
      box-shadow: 0 4px 20px 0 rgb(0 0 0 / 14%), 0 7px 10px -5px rgb(255 152 0 / 40%);
  }
  .card.bg-success, .card .card-header-success .card-icon, .card .card-header-success .card-text, .card .card-header-success:not(.card-header-icon):not(.card-header-text), .card.card-rotate.bg-success .back, .card.card-rotate.bg-success .front {
      background: linear-gradient(
  60deg
  ,#21B849,#04CF3A);
  }

  #reportrange {
    background-color: lavender !important;
    font-weight: bold; 
    cursor: pointer; 
    border: 1px solid #ccc; 
    border-radius: 5px; 
    width: 100%; 
    height:30px; 
    text-align: left; 
    display:flex; 
    align-items: center; 
    justify-content: space-between;
    padding-left: 7px;
}
</style>
<div class="col-xs-12 col-sm-11">
  @if ($errors->has('form'))
    <div class="alert alert-danger">
        {{ $errors->first('form') }}
    </div>
  @endif
  <form id="conceptpoform" method="POST" action="{{ route('purchaseorders.detailbyconcept') }}">
    {{ csrf_field() }}
    <h3>Purchase Orders by Concept</h3>
    <div class="form-group row {{ $errors->has('concept_id') ? 'has-error' : '' }}">
      <div class="col-sm-4">
          <select id="concept_id" name="concept_id" class="form-control concepts" style="width:100%;">
            <option></option>
            @foreach ($concepts as $cnpt)
              <option value="{{ $cnpt->id }}" 
                {{ old('concept_id', isset($concept) ? $concept->id : '') == $cnpt->id ? 'selected' : '' }}>
                {{ $cnpt->name }}
            </option>
            @endforeach
          </select>
      </div>
    </div>
    <div class="form-group row {{ $errors->has('country') ? 'has-error' : '' }}">
      <div class="col-sm-4">
          <select id="country" name="country" class="form-control country" style="width:100%;">
            <option></option>
            @foreach ($countries as $ctry)
                <option value="{{$ctry}}" {{ old("country", isset($country) ? $country : '') == $ctry ? 'selected' : '' }}>{{$ctry}}</option>
            @endforeach
          </select>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-4">
        <div class="form" id="reportrange">
          <span id="rangedata"></span> <i class="fa fa-caret-down" style="padding-right: 6px;"></i>
        </div>
      </div>
    </div>
    <input type="hidden" name="start_date" id="start_date">
    <input type="hidden" name="end_date" id="end_date">

    <div class="form-group row">
      <div class="col-sm-4">
        <button id="getreport" type="submit" class="btn btn-success btn-sm">Generate Report</button>
      </div>
    </div>
  </form>

    @if (isset($concept))
      @if (isset($result_concepts) && count($result_concepts) > 0) 
        <br>
        <h3 id="purchaseorderstitle">Purchase Orders for {{$concept->name}} from {{$start}} to {{$end}}</h3>
        @perm('admin_concept_budgets')
          @if (isset($concept_budget) && $result_concepts[0]->total_po_amount > $concept_budget->budget)
            {{-- Budget Exceeded --}}
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">trending_up</i>
                        </div>
                        <p class="card-category">Budget Exceeded</p>
                        <h3 class="card-title">
                            <span class="text-danger">
                                -{{ number_format((($result_concepts[0]->total_po_amount - $concept_budget->budget) / $concept_budget->budget) * 100, 2) }}%
                            </span>
                        </h3>
                    </div>
                    <div class="card-footer">
                      <div class="stats" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                          <span>Total: ${{ number_format($result_concepts[0]->total_po_amount, 2) }}</span>
                          <span>Total Received: ${{ number_format($result_concepts[0]->total_po_received_amount, 2) }}</span>
                      </div>
                  </div>
                </div>
            </div>
          @elseif(isset($concept_budget) && $result_concepts[0]->total_po_amount < $concept_budget->budget)
            {{-- Budget Available --}}
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">trending_down</i>
                        </div>
                        <p class="card-category">Budget Available</p>
                        <h3 class="card-title">
                            <span class="text-success">
                                {{ number_format((($concept_budget->budget - $result_concepts[0]->total_po_amount) / $concept_budget->budget) * 100, 2) }}%
                            </span>
                        </h3>
                    </div>
                    <div class="card-footer">
                      <div class="stats" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                          <span>Total: ${{ number_format($result_concepts[0]->total_po_amount, 2) }}</span>
                          <span>Total Received: ${{ number_format($result_concepts[0]->total_po_received_amount, 2) }}</span>
                      </div>
                  </div>
                </div>
            </div>
          @endif
        @endperm
        <table id="purchaseorders" class="table nowrap table-hover table-condensed table-striped table-bordered" style="font-size:95%; background-color:whitesmoke;">
            <thead>
              <tr style="background-color:Lavender">
                <th>Total PO Qty</th>
                <th>Total PO Amount</th>
                <th>Total PO Qty Received</th>
                <th>Total PO Received Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($result_concepts as $co)
                <tr>
                  <th style="text-align: right;">{{$co->total_po_qty }}</th>
                  <th style="text-align: right;">${{ number_format($co->total_po_amount, 2) }}</th>
                  <th style="text-align: right;">{{$co->total_po_qty_received }}</th>
                  <th style="text-align: right;">${{ number_format($co->total_po_received_amount, 2) }}</th>
                </tr>
              @endforeach
            </tbody>
        </table>    
        <br>
      @else
        <h4>No Purchse Orders found for {{ $concept->name}} from {{$start}} to {{$end}}</h4>
      @endif
      @if (isset($result_warehouses) && count($result_warehouses) > 0)
        <table id="purchaseorders_warehouse" class="table nowrap table-hover table-condensed table-striped table-bordered" style="font-size:95%; background-color:whitesmoke;">
          <thead>
            <tr style="background-color:Lavender">
              <th>Warehouse</th>
              <th>Total PO Qty</th>
              <th>Total PO Amount</th>
              <th>Total PO Qty Received</th>
              <th>Total PO Received Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($result_warehouses as $warehouse)
              <tr>
                <td>{{$warehouse->ShipToName}}</td>
                <td style="text-align: right;">{{$warehouse->total_po_qty }}</td>
                <td style="text-align: right;">${{number_format($warehouse->total_po_amount, 2) }}</td>
                <td style="text-align: right;">{{$warehouse->total_po_qty_received }}</td>                
                <td style="text-align: right;">${{number_format($warehouse->total_po_received_amount, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
              <tr style="background-color:Lavender">
                <th>TOTALS</th>
                <th style="text-align: right;">{{$result_warehouses->sum('total_po_qty')}}</th>
                <th style="text-align: right;">${{number_format($result_warehouses->sum('total_po_amount'), 2)}}</th>
                <th style="text-align: right;">{{$result_warehouses->sum('total_po_qty_received')}}</th>
                <th style="text-align: right;">${{number_format($result_warehouses->sum('total_po_received_amount'), 2)}}</th>
              </tr>          
          </tfoot>
        </table>    
        <br>
      @endif

      @if (isset($result_details) && count($result_details) > 0)
        <table id="purchaseorder_details" class="table table-hover table-condensed table-striped table-bordered" style="font-size:95%; background-color:whitesmoke;">
          <thead>
            <tr style="background-color:Lavender">
              <th>Vendor</th>
              <th>Ship To</th>
              <th>Date</th>
              <th>Status</th>
              <th nowrap>PO #</th>
              <th>Item</th>
              <th>Qty</th>
              <th>Amount</th>
              <th>Received Qty</th>
              <th>Received Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($result_details as $po)
              <tr>
                <td nowrap>{{$po->VendorName}}</td>
                <td nowrap>{{$po->ShipToName}}</td>
                <td nowrap>{{$po->Date}}</td>
                <td nowrap>{{$po->Status}}</td>
                <td>{{$po->PO}}</td>
                <td>{{$po->item}}</td>
                <td style="text-align: right;">{{$po->total_po_qty }}</td>
                <td style="text-align: right;">${{number_format($po->total_po_amount, 2) }}</td>
                <td style="text-align: right;">{{$po->total_po_qty_received}}</td>
                <td style="text-align: right;">@if(isset($po->total_po_received_amount)) $ @endif{{isset($po->total_po_received_amount) ? number_format($po->total_po_received_amount, 2) : ''}}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr style="background-color:Lavender">
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>TOTALS</th>
              <th style="text-align: right;"></th>
              <th style="text-align: right;"></th>
              <th style="text-align: right;"></th>              
              <th style="text-align: right;"></th>
            </tr>
          </tfoot>
        </table>
        <br>
      @endif
    @endif
    {{--<a id="scrolltop"></a>--}}
    <div class="progress-wrap">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
      </svg>
    </div>    
</div>
<script>
  /*var btn = $('#scrolltop');
  $(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop:0}, '300');
  });*/

  $(document).ready( function () {

    $('#conceptpoform').submit(function(){
      if (!$('#concept_id').val()) {
        $.alert({
          title: 'Encountered an error!',
          content: 'Concept is required',
          type: 'orange',
          buttons: {
            close: function() {
              $('#concept_id').focus();
            }
          }
        });
        return false;
      }
    });

    var currentMonthName = moment().format('MMMM');
    var currentYearName = moment().format('YYYY');

    $('.concepts').select2({allowClear: true, placeholder: '   Select Concept...'});
    $('.country').select2({placeholder: '   Select Country...'});

    ttl = $('#purchaseorderstitle').text().replace(/\s+/g, '_').replace(/,/g, '_');
    var fn = ttl + '_' + moment().format('YYYYMMDD');
    var table = $('#purchaseorder_details').DataTable( {
      fixedHeader : true,
      searchPanes: {
        columns: [ 0, 1, 3],
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
      order: [[ 2, 'asc'], [1, 'asc'], [4, 'asc']],
      dom: 'PBf<"toolbar">rt',
    
      columnDefs: [{
        searchPanes: {
            show: true
        },
        targets: [0, 1, 3]
      }],                  
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
                sheetName: 'POs',
                exportOptions: {
                    stripHtml: true
                },
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var col = $('col', sheet);
                    var ocellXfs = $('cellXfs', xlsx.xl['styles.xml']);
                    ocellXfs.append('<xf numFmtId="170" fontId="0" fillId="0" borderId="0" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'+'</xf>');
                    ocellXfs.attr('count', ocellXfs.attr('count') +1 );
                    var numFmts = $('numFmts', xlsx.xl['styles.xml']);
                    numFmts.append('<numFmt formatCode="$* #,##0.00"  numFmtId="170" />');
                    numFmts.attr('count', numFmts.attr('count') +1 );
                    var oxf = $('xf', xlsx.xl['styles.xml']);
                    var styleIndex = oxf.length;
                    $('row c[r^="H"]', sheet).attr( 's', styleIndex - 2 );
                    $('row c[r^="J"]', sheet).attr( 's', styleIndex - 2 );
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

          poqty = api
            .column(6, { search:'applied' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
          }, 0);

          poamount = api
            .column(7, { search:'applied' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
          }, 0);

          poqtyreceived = api
            .column(8, { search:'applied' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
          }, 0);

          poamountreceived = api
            .column(9, { search:'applied' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
          }, 0);   

          $(api.column(6).footer()).html(poqty);
          $(api.column(7).footer()).html('$' + poamount.toFixed(2));
          $(api.column(8).footer()).html(poqtyreceived);
          $(api.column(9).footer()).html('$' + poamountreceived.toFixed(2));
      },    
    });

  $(function() {
    var s ="{{$start}}";
    var e ="{{$end}}";
    var start = moment(s);
    var end = moment(e);
    var label = "This Month";

    function cb(start, end, label) {
        $('#chartheader span').html(label);
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        var from = start.format('YYYY-MM-DD');
        var to = end.format('YYYY-MM-DD');
        $('#start_date').val(from);
        $('#end_date').val(to);
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: moment(),
        autoUpdateInput: true,
        ranges: {
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
        }
    }, cb);
    cb(start, end, label);
  });

  $('.dataTables_length select').select2({
        minimumResultsForSearch: -1,
        width: 'auto',
        allowClear: false,
        height: '100%',
    });
  });
</script>
@endsection