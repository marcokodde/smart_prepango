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

  /*input[type="search"]::-webkit-search-cancel-button {
    -webkit-appearance: searchfield-cancel-button;
  }*/

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

th.numeric, td.numeric {
  text-align: right !important;
} 
</style>
<div class="col-xs-12 col-sm-11">
  <form method="POST" action="{{ route('purchaseorders.detailbyvendor') }}">
    {{ csrf_field() }}
    <h3>Purchase Orders by Vendor</h3>
    <div class="form-group row {{ $errors->has('vendor_id') ? 'has-error' : '' }}">
      <div class="col-sm-4">
          <select id="vendor_id" name="vendor_id" class="form-control vendors" style="width:100%;">
            <option></option>  
            @foreach ($vendors as $vdr)
                <option value="{{$vdr->id}}" @if (isset($vendor) && $vdr->id == $vendor->id) selected @endif>{{$vdr->Name}}</option>
            @endforeach 
          </select>
      </div>
    </div>
    <div class="form-group row {{ $errors->has('start') ? 'has-error' : '' }}">
        <div class="col-sm-4">
            <input type="date" class="form-control" id="start" name="start" placeholder="FRom Date" value="{{ old('start', !empty($start) ? $start : date('Y-m-d') ) }}">
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
      <h4 id="purchaseorderstitle">Purchse Orders for {{$vendor->Name}} from {{$start}} to {{$end}}</h4>
      <table id="purchaseorders" class="table nowrap table-hover table-condensed table-striped table-bordered">
        <thead>
          <tr style="background-color:Lavender">
            <th>Ship To</th>
            <th>PO</th>
            <th>Date</th>
            <th>Status</th>
            <th>Product</th>
            <th>Rate</th>
            <th>Ordered</th>
            <th>Total Ordered</th>
            <th>Received</th>
            <th>Total Received</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchaseorders as $po)
            <tr>
              <td>{{$po->ShipTo}}</td>
              <td>{{$po->PO}}</td>
              <td>{{$po->Date}}</td>
              <td>{{$po->Status}}</td>
              <td>{{$po->Product}}</td>
              <td class="numeric">{{number_format($po->Rate, 2)}}</td>
              <td class="numeric">{{$po->OrderedUnits}}</td>
              <td class="numeric">{{number_format($po->TotalOrdered, 2)}}</td>
              <td class="numeric">{{$po->ReceivedUnits}}</td>
              <td class="numeric">{{number_format($po->TotalReceived, 2)}}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color:Lavender">
              <th colspan="5"></th>
              <th class="numeric"></th>
              <th class="numeric"></th>
              <th class="numeric"></th>
              <th class="numeric"></th>
              <th class="numeric"></th>              
            </tr>          
        </tfoot>
    </table>    
    <br>
  @elseif (isset($vendor))
    <h4>No Purchse Orders found for {{$vendor->Name}} from {{$start}} to {{$end}}</h4>
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
      $('.vendors').select2({placeholder: '   Select Vendor...'});
      ttl = $('#purchaseorderstitle').text().replace(/\s+/g, '_').replace(/,/g, '_');
      var fn = ttl + '_' + moment().format('YYYYMMDD');
      $('#purchaseorders').DataTable( {
        fixedHeader : true,
        searchPanes: {
          columns: [ 0, 3 ],
          cascadePanes: true,  
          viewTotal: true,
          layout: 'columns-2'
        },        
        paging: false,
        searching: true,
        language: {
          searchPlaceholder: "Search...",
          search: "_INPUT_",
          sLengthMenu: "_MENU_",
        },      
        order: [[ 2, 'asc'], [1, 'asc'], [4, 'asc']],
        dom: 'PBfrt',
        columnDefs: [{
          searchPanes: {
              show: true
          },
          targets: [0, 3]
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
                  sheetName: '{{$vendor}} POs',
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

            rate = api
              .column(5, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            ordered = api
              .column(6, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            valordered = api
              .column(7, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            received = api
              .column(8, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            valreceived = api
              .column(9, { search:'applied' })
              .data()
              .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
            }, 0);

            $(api.column(5).footer()).html(rate.toFixed(2));
            $(api.column(6).footer()).html(ordered);
            $(api.column(7).footer()).html(valordered.toFixed(2));
            $(api.column(8).footer()).html(received);
            $(api.column(9).footer()).html(valreceived.toFixed(2));
        },
      });
    });
  </script>
@endsection