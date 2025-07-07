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

  .select2-container--default .select2-selection--single{
    background-color: lavender;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: Lavender;
    color: #393A3B;
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
</style>
<div class="col-xs-12 col-sm-9">
  <form method="POST" action="{{ route('purchaseorders.partialpendingrpt') }}">
    {{ csrf_field() }}
    <h3>Partial and Pending Purchase Orders by Vendor</h3>
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
    <div class="form-group row">
      <div class="col-sm-4">
        <button id="getreport" type="submit" class="btn btn-success btn-sm">Generate Report</button>
      </div>
    </div>
  </form>
  @if (isset($purchaseorders) && count($purchaseorders) > 0) 
      <br>
      <h4 id="purchaseorderstitle">Partial and Pending Purchse Orders for {{$vendor->Name}}</h4>
      <table id="purchaseorders" class="table nowrap table-hover table-condensed table-striped table-bordered">
        <thead>
          <tr style="background-color:Lavender">
            <th>Ship To</th>
            <th>Date</th>
            <th>PO</th>
            <th>Units</th>
            <th>Product</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchaseorders as $po)
            <tr>
              <td>{{$po->ShipTo}}</td>
              <td>{{$po->Date}}</td>
              <td>{{$po->PO}}</td>
              <td>{{$po->Units}}</td>
              <td>{{$po->Product}}</td>
            </tr>
          @endforeach
        </tbody>
    </table>    
    <br>
  @elseif (isset($vendor))
    <h4>No Partial or Pending Purchse Orders found for {{$vendor->Name}}</h4>
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
        paging: false,
        searching: true,
        language: {
          searchPlaceholder: "Search...",
          search: "_INPUT_",
          sLengthMenu: "_MENU_",
        },      
        order: [[ 4, 'desc'], [3, 'desc'], [1, 'desc']],
        dom: 'Bfrt',
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
                  sheetName: 'Partial & Pending POs',
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
      });
    });
  </script>
@endsection