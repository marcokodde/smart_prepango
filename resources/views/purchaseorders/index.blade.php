@extends('layouts.app')
@section('content')
<style>
  .table-striped>tbody>tr:nth-child(even) {
      background-color: LightCyan;
  }

  select[name="machines_length"] {
      border-radius: 5px;
  }

  input[type=search] {
      width: 250px;
      height: 30px;
      border: 1px solid #ccc;
      padding: 0px 0;
      padding-right: 15px;
      padding-left: 15px;
      border-radius: 5px;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      color: white !important;
      border: 1px solid #C0C0C0!important;
      background-color: #C0C0C0!important;
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #C0C0C0), color-stop(100%, #C0C0C0))!important;
      background: -webkit-linear-gradient(top, #C0C0C0 0%, #C0C0C0 100%)!important;
      background: -moz-linear-gradient(top, #C0C0C0 0%, #C0C0C0 100%)!important;
      background: -ms-linear-gradient(top, #C0C0C0 0%, #C0C0C0 100%)!important;
      background: -o-linear-gradient(top, #C0C0C0 0%, #C0C0C0 100%)!important;
      background: linear-gradient(to bottom, #C0C0C0 0%, #C0C0C0 100%)!important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
      color: #333 !important;
      border: 1px solid #979797;
      background-color: white;
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, Lavender), color-stop(100%, Lavender));
      background: -webkit-linear-gradient(top, Lavender 0%, Lavender 100%);
      background: -moz-linear-gradient(top, Lavender 0%, Lavender 100%);
      background: -ms-linear-gradient(top, Lavender 0%, Lavender 100%);
      background: -o-linear-gradient(top, Lavender 0%, Lavender 100%);
      background: linear-gradient(to bottom, Lavender 0%, Lavender 100%);
  }

  #GlobalFilters td{
   padding-right: 10px;
  }

  .rangepicker{
    margin-right: 10px;
    padding-right: 10px;
    background: lavender;
    background-color: transparent;
    cursor: pointer;
    border: 1px solid #aaa;
    border-radius: 4px;
    width: 320px;
    height: 28px;
    text-align: left;
    display:flex;
    align-items: center;
    justify-content: space-between;
    background-color: lavender !important;
}

#rangedata {
    font-weight: bold;
}

#ClearGlobalFilters {
  height: 28px:
}

input[type=search] {
    height: 30px;
    border: 1px solid #ccc;
    padding: 0px 0;
    padding-right: 15px;
    padding-left: 15px;
    border-radius: 5px;
    background-color: lavender !important;
}

  #incomplete_orders_wrapper, #partial_purchases_wrapper, #pending_purchases_wrapper, #received_purchases_wrapper {
    justify-content: space-between;
  }

  select[name="incomplete_orders_length"], select[name="partial_purchases_length"], select[name="pending_purchases_length"], select[name="received_purchases_length"] {
    border-radius: 5px;
    background-color: lavender !important;
  }

  div.dtsp-searchPanes {
    justify-content: space-evenly !important;
  }

  .select2-container--default .select2-selection--single{
    background-color: lavender;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: Lavender;
    color: #393A3B;
  }

  .closeout {
    width: 120px !important;
    height: 30px !important;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .dataTables_length {
    margin-right: 5px;
    height: 30px;
  }
</style>
@include('purchaseorders.sidebar')
<div class="col-xs-12 col-sm-10">
  <br/>

  <table id="GlobalFilters">
    <tr>
      <td>
        <label for="POSearch">PO Number</label>
        <input type="search" placeholder="Search PO Number..." id="POSearch" name="POSearch">
      </td>
      <td>
        <label for="VendorSearch">Vendor</label>
        <input type="search" placeholder="Search Vendor..." id="VendorSearch" name="VendorSearch">        
      </td>
      <td>
        <button class="btn btn-primary btn-sm" id="ClearGlobalFilters"><i class="fa fa-remove"></i> Clear Filters</button>
      </td>
    </tr>
  </table>

  <h2>Incomplete Purchase Orders</h2>
  <table id="incomplete_orders" class="table table-hover table-condensed table-striped table-bordered mytables" style="font-size:98%">
    <thead>
      <tr style="background-color:Lavender">
        <th>ID</th>
        <th>Date</th>
        <th>PO Number</th>
        <th>Vendor</th>
        <th>Ship To</th>
        <th>Status</th>
        <th>In Quickbooks</th>
        <th>Closeout</th>
        <th></th>
        @perm('edit_po')
          <th></th>
        @endperm
        <th></th>
        <th></th>
      </tr>
    </thead>
    @if(isset($incomplete_orders))
    <tbody> 
        @foreach($incomplete_orders as $po)
        <tr>
         <td>{{$po->id}}</td>
         <td>{{$po->TxnDate}}</td>
          <td>{{$po->RefNumber}}</td>
          <td>{{$po->VendorName}}</td>
          <td>{{$po->ShipToName}}</td>
          <td>{{strtoupper($po->received_state)}}</td>
          <td>{{strtoupper($po->in_quickbooks)}}</td>
          <td>{{($po->closeout == 0) ? 'NO' : 'YES'}}</td>
          <td class="text-center"><a href="{{action('PurchaseOrderController@show', $po->id)}}" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>
          @perm('edit_po')
            <td class="text-center"><a href="{{action('PurchaseOrderController@edit', $po->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>
          @endperm
          <td><a href="/purchaseorders/pdf/{{$po->id}}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>
          <td><a href="/purchaseorders/pdfDetail/{{$po->id}}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>
        </tr>
        @endforeach
    </tbody>
    @endif
  </table>

  <h2>Partial Purchase Orders</h2>
  <table id="partial_purchases" class="table table-hover table-condensed table-striped table-bordered mytables" style="font-size:98%">
    <thead>
      <tr style="background-color:Lavender">
        <th>ID</th>
        <th>Date</th>
        <th>PO Number</th>
        <th>Vendor</th>
        <th>Ship To</th>
        <th>Status</th>
        <th>In Quickbooks</th>
        <th>Closeout</th>
        <th></th>
        @perm('edit_po')
        <th></th>
        @endperm
        <th></th>
        <th></th>
      </tr>
    </thead>
    @if(isset($partial_orders))
    <tbody> 
        @foreach($partial_orders as $po)
        <tr>
         <td>{{$po->id}}</td>
         <td>{{$po->TxnDate}}</td>
          <td>{{$po->RefNumber}}</td>
          <td>{{$po->VendorName}}</td>
          <td>{{$po->ShipToName}}</td>
          <td>{{strtoupper($po->received_state)}}</td>
          <td>{{strtoupper($po->in_quickbooks)}}</td>
          <td>{{($po->closeout == 0) ? 'NO' : 'YES'}}</td>
          <td class="text-center"><a href="{{action('PurchaseOrderController@show', $po->id)}}" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>
          @perm('edit_po')
          <td class="text-center"><a href="{{action('PurchaseOrderController@edit', $po->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>
          @endperm
          <td><a href="/purchaseorders/pdf/{{$po->id}}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>
          <td><a href="/purchaseorders/pdfDetail/{{$po->id}}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>
        </tr>
        @endforeach
    </tbody>
    @endif
  </table>

  <h2>Pending Purchase Orders</h2>
  <table id="pending_purchases" class="table table-hover table-condensed table-striped table-bordered mytables" style="font-size:98%">
    <thead>
      <tr style="background-color:Lavender">
        <th>ID</th>
        <th>Date</th>
        <th>PO Number</th>
        <th>Vendor</th>
        <th>Ship To</th>
        <th>Status</th>
        <th>In Quickbooks</th>
        <th>Closeout</th>
        <th></th>
        @perm('edit_po')
        <th></th>
        @endperm
        <th></th>
        <th></th>
      </tr>
    </thead>
    @if(isset($notreceived_orders))
    <tbody> 
        @foreach($notreceived_orders as $po)
        <tr>
         <td>{{$po->id}}</td>
         <td>{{$po->TxnDate}}</td>
          <td>{{$po->RefNumber}}</td>
          <td>{{$po->VendorName}}</td>
          <td>{{$po->ShipToName}}</td>
          @if (auth()->user()->hasPermission('edit_po'))
            <td><a href="{{action('PurchaseOrderController@receivePurchaseOrder', $po->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-check-square-o"></i> Receive</a></td>
          @else 
            <td>{{strtoupper($po->received_state)}}</td>
          @endif
          <td>{{strtoupper($po->in_quickbooks)}}</td>
          <td>{{($po->closeout == 0) ? 'NO' : 'YES'}}</td>
          <td class="text-center"><a href="{{action('PurchaseOrderController@show', $po->id)}}" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>
          @perm('edit_po')
          <td class="text-center"><a href="{{action('PurchaseOrderController@edit', $po->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>
          @endperm
          <td><a href="/purchaseorders/pdf/{{$po->id}}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>
          <td><a href="/purchaseorders/pdfDetail/{{$po->id}}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>
        </tr>
        @endforeach
    </tbody>
    @endif
  </table>

  <h2>Received Purchase Orders</h2>
  <table id="received_purchases" name="received_purchases" class="table table-hover table-condensed table-striped table-bordered rxpo" style="font-size:98%">
    <thead>
      <tr style="background-color:Lavender">
        <th>ID</th>
        <th>Date</th>
        <th>PO Number</th>
        <th>Vendor</th>
        <th>Ship To</th>
        <th>Status</th>
        <th>In Quickbooks</th>
        <th>Closeout</th>
        <th></th>
        @perm('edit_po')
        <th></th>
        @endperm
        <th></th>
        <th></th>
      </tr>
    </thead>
    @if(isset($received_orders))
    <tbody> 
        @foreach($received_orders as $po)
        <tr>
         <td>{{$po->id}}</td>
         <td>{{$po->TxnDate}}</td>
          <td>{{$po->RefNumber}}</td>
          <td>{{$po->VendorName}}</td>
          <td>{{$po->ShipToName}}</td>
          <td>{{strtoupper($po->received_state)}}</td>
          <td>{{strtoupper($po->in_quickbooks)}}</td>
          <td>{{($po->closeout == 0) ? 'NO' : 'YES'}}</td>
          <td class="text-center"><a href="{{action('PurchaseOrderController@show', $po->id)}}" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>
          @perm('edit_po')
            <td class="text-center"><a href="{{action('PurchaseOrderController@edit', $po->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>
          @endperm
          <td><a href="/purchaseorders/pdf/{{$po->id}}" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>
          <td><a href="/purchaseorders/pdfDetail/{{$po->id}}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>
        </tr>
        @endforeach
    </tbody>
    @endif
  </table>
  <br/>
</div>
<script>
    $(document).ready( function () {
      var incompleteorders = $('#incomplete_orders').DataTable({
          pageLength : 10,
          order: [[ 1, "desc" ]],
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          searchPanes: {
            columns: [3,4],
            cascadePanes: true,  
            viewTotal: true,
            layout: 'columns-2'
          },
          dom: 'Plfr<"toolbar">tip',
          columnDefs: [{
            searchPanes: {
                show: true
            },
            targets: [3, 4]
          }],          
          language: {
              searchPlaceholder: "Search...",
              search: "_INPUT_",
              sLengthMenu: "_MENU_",
              searchPanes: {
                  count: '{total} found',
                  countFiltered: '{shown} / {total}',
              }
          },
      });

      $('div.toolbar', incompleteorders.table().container()).html('<div class="col-sm-5 rangepicker" id="daterange_incompleteorders"><span id="rangedata"></span> <i class="fa fa-caret-down"></i></div><select class="form-control closeout closeoutincomplete" name="closeoutincomplete" id="closeoutincomplete"><option></option><option value="NO">NO</option><option value="YES">YES</option></select>');
      $('.closeoutincomplete').select2({closeOnSelect: true, allowClear: true, placeholder: '   Closeout...'}).on("select2:unselecting", function(e) {
          var self = $(this);
          setTimeout(function() {
              self.select2('close');
          }, 0);
      });

      var partialorders = $('#partial_purchases').DataTable({
          pageLength : 10,
          order: [[ 1, "desc" ]],
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          searchPanes: {
            columns: [3,4],
            cascadePanes: true,  
            viewTotal: true,
            layout: 'columns-2'
          },
          dom: 'Plfr<"toolbar">tip',
          columnDefs: [{
            searchPanes: {
                show: true
            },
            targets: [3, 4]
          }],          
          language: {
              searchPlaceholder: "Search...",
              search: "_INPUT_",
              sLengthMenu: "_MENU_",
              searchPanes: {
                  count: '{total} found',
                  countFiltered: '{shown} / {total}',
              }
          },
      });

      $('div.toolbar', partialorders.table().container()).html('<div class="col-sm-5 rangepicker" id="daterange_partialorders"><span id="rangedata"></span> <i class="fa fa-caret-down"></i></div><select class="form-control closeout closeoutpartial" name="closeoutpartial" id="closeoutpartial"><option></option><option value="NO">NO</option><option value="YES">YES</option></select>');
      $('.closeoutpartial').select2({closeOnSelect: true, allowClear: true, placeholder: '   Closeout...'}).on("select2:unselecting", function(e) {
          var self = $(this);
          setTimeout(function() {
              self.select2('close');
          }, 0);
      });      

      var pendingorders = $('#pending_purchases').DataTable({
          pageLength : 10,
          order: [[ 1, "desc" ]],
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          searchPanes: {
            columns: [3,4],
            cascadePanes: true,  
            viewTotal: true,
            layout: 'columns-2'
          },
          dom: 'Plfr<"toolbar">tip',
          columnDefs: [{
            searchPanes: {
                show: true
            },
            targets: [3, 4]
          }],          
          language: {
              searchPlaceholder: "Search...",
              search: "_INPUT_",
              sLengthMenu: "_MENU_",
              searchPanes: {
                  count: '{total} found',
                  countFiltered: '{shown} / {total}',
              }
          },
      });

      $('div.toolbar', pendingorders.table().container()).html('<div class="col-sm-5 rangepicker" id="daterange_pendingorders"><span id="rangedata"></span> <i class="fa fa-caret-down"></i></div><select class="form-control closeout closeoutpending" name="closeoutpending" id="closeoutpending"><option></option><option value="NO">NO</option><option value="YES">YES</option></select>');
      $('.closeoutpending').select2({closeOnSelect: true, allowClear: true, placeholder: '   Closeout...'}).on("select2:unselecting", function(e) {
          var self = $(this);
          setTimeout(function() {
              self.select2('close');
          }, 0);
      });

      $('.closeoutincomplete').change(function() {
          if('' == this.value) {
              incompleteorders.tables().columns(7).search('').draw();
          } else {
              incompleteorders.tables().columns(7).search('\\b' + $(this).val() + '\\b', true, false).draw();
          }
      });

      $('.closeoutpartial').change(function() {
          if('' == this.value) {
              partialorders.tables().columns(7).search('').draw();
          } else {
              partialorders.tables().columns(7).search('\\b' + $(this).val() + '\\b', true, false).draw();
          }
      });

      $('.closeoutpending').change(function() {
          if('' == this.value) {
              pendingorders.tables().columns(7).search('').draw();
          } else {
              pendingorders.tables().columns(7).search('\\b' + $(this).val() + '\\b', true, false).draw();
          }
      });      

      var rxpotable = $('#received_purchases').DataTable({
          pageLength : 10,
          order: [[ 1, "desc" ]],
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          searchPanes: {
            columns: [3,4],
            cascadePanes: true,  
            viewTotal: true,
            layout: 'columns-2'
          },
          dom: 'Plfr<"toolbar">tip',
          columnDefs: [{
            searchPanes: {
                show: true
            },
            targets: [3, 4]
          }],          
          language: {
              searchPlaceholder: "Search...",
              search: "_INPUT_",
              sLengthMenu: "_MENU_",
              searchPanes: {
                  count: '{total} found',
                  countFiltered: '{shown} / {total}',
              }
          },
      });
      
        $('.restockmachines').on('change',function(){
            var val = $.fn.dataTable.util.escapeRegex(this.options[this.selectedIndex].text);
            crtable.tables().columns(0).search(val ? '^' + val + '$' : '', true, false).draw();
        });


      $('#POSearch').on( 'keyup click input', function () {
        incompleteorders.tables().columns(2).search($(this).val()).draw();
        partialorders.tables().columns(2).search($(this).val()).draw();
        pendingorders.tables().columns(2).search($(this).val()).draw();
        rxpotable.tables().columns(2).search($(this).val()).draw();
      });

      $('#VendorSearch').on( 'keyup click input', function () {
        incompleteorders.tables().columns(3).search($(this).val()).draw();
        partialorders.tables().columns(3).search($(this).val()).draw();
        pendingorders.tables().columns(3).search($(this).val()).draw();        
        rxpotable.tables().columns(3).search($(this).val()).draw();
      });

      $('#ClearGlobalFilters').click(function () {
          document.getElementById('POSearch').value = '';
          document.getElementById('VendorSearch').value = '';
          incompleteorders.tables().columns(2).search('').columns(3).search('').draw();
          partialorders.tables().columns(2).search('').columns(3).search('').draw();
          pendingorders.tables().columns(2).search('').columns(3).search('').draw();          
          rxpotable.tables().columns(2).search('').columns(3).search('').draw();
      } );

      $('[data-toggle="tooltip"]').tooltip({
          tooltipClass: "ui-tooltip",
          content: function() {
              var element = $(this);
              return element.attr("title");
          },
          position: {
              my: 'left center',
              at: 'right+5 center',
              collision: 'none',
              using: function( position, feedback ) {
                  $( this ).css( position );
              }
          },
      }).on('mouseleave click', function () {
        $('.ui-tooltip').fadeOut('fast', function() {
            $('.ui-tooltip').hide();
        });
      });

 
      $('div.toolbar', rxpotable.table().container()).html('<div class="col-sm-5 rangepicker" id="daterange"><span id="rangedata"></span> <i class="fa fa-caret-down"></i></div> <select class="form-control closeout closeoutreceived" name="closeoutreceived" id="closeoutreceived"><option></option><option value="NO">NO</option><option value="YES">YES</option></select>');
      $('.closeoutreceived').select2({closeOnSelect: true, allowClear: true, placeholder: '   Closeout...'}).on("select2:unselecting", function(e) {
          var self = $(this);
          setTimeout(function() {
              self.select2('close');
          }, 0);
      });

      $('.closeoutreceived').change(function() {
          if('' == this.value) {
              rxpotable.tables().columns(7).search('').draw();
          } else {
              rxpotable.tables().columns(7).search('\\b' + $(this).val() + '\\b', true, false).draw();
          }
      });

      var drworking=$.confirm({
          lazyOpen: true,
          icon: 'fa fa-spinner fa-spin',
          title: 'Working!',
          content: 'Sit back, we are processing your request!'
      });

      $(function() {
          var start = moment("{{$start}}");
          var end = moment("{{$end}}");
          var state = "Received"
          var label = "Last 90 Days";
          $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          function cb(start, end, label) {
            var from = start.format('YYYY-MM-DD');
            var to = end.format('YYYY-MM-DD');
            $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            drworking.open();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax( {
                url: "{{ url('purchaseorders/getreceivedorders') }}",
                method: 'get',
                data: {
                    date_from: from,
                    date_to: to,
                    state: state
                },
                success: function ( data ) {
                  rxpotable.clear().draw();
                  $.each(data[0], function (key,value) {
                    var in_qb = value.in_quickbooks.toUpperCase();
                    var closeout = (value.closeout==0) ? 'NO' : 'YES';
                    var newRow = 
                    '<tr>'+
                    '<td>'+value.id+'</td>'+
                    '<td>'+value.TxnDate+'</td>'+
                      '<td>'+value.RefNumber+'</td>'+
                      '<td>'+value.VendorName+'</td>'+
                      '<td>'+value.ShipToName+'</td>'+
                      '<td>'+value.received_state+'</td>'+
                      '<td>'+in_qb+'</td>'+
                      '<td>'+closeout+'</td>'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>'+
                      '@perm("edit_po")'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'/edit" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>'+
                      '@endperm'+
                      '<td><a href="/purchaseorders/pdf/'+value.id+'" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>'+
                      '<td><a href="/purchaseorders/pdfDetail/'+value.id+'" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>'+
                    '</tr>';
                    rxpotable.row.add($(newRow)).draw();
                  });
                  rxpotable.searchPanes.rebuildPane();
                  drworking.close();
                },
                error: function (request, status, error) {
                  $.alert({
                      title: 'Encountered an error!',
                      content: request.responseText,
                      type: 'red',
                  });
                }
            });     
          }
          $('#daterange').daterangepicker({
              startDate: start,
              endDate: end,
              maxDate: moment().subtract(0, 'days'),
              ranges: {
                  'Last 90 Days': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                  'This Year': [moment().startOf('year'), moment().endOf('year')],
                  'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
              }
          }, cb);
        });
      
      /*daterange_incompleteorders*/
      $(function() {
          var start = moment("{{$start}}");
          var end = moment("{{$end}}");
          var state = "PENDING";
          var label = "Last 90 Days";
          $('#daterange_incompleteorders span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          function cb(start, end, label) {
            var from = start.format('YYYY-MM-DD');
            var to = end.format('YYYY-MM-DD');
            $('#daterange_incompleteorders span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            drworking.open();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax( {
                url: "{{ url('purchaseorders/getreceivedorders') }}",
                method: 'get',
                data: {
                    date_from: from,
                    date_to: to,
                    state: state
                },
                success: function ( data ) {
                  incompleteorders.clear().draw();
                  $.each(data[0], function (key,value) {
                    var in_qb = value.in_quickbooks.toUpperCase();
                    var closeout = (value.closeout==0) ? 'NO' : 'YES';
                    var newRow = 
                    '<tr>'+
                    '<td>'+value.id+'</td>'+
                    '<td>'+value.TxnDate+'</td>'+
                      '<td>'+value.RefNumber+'</td>'+
                      '<td>'+value.VendorName+'</td>'+
                      '<td>'+value.ShipToName+'</td>'+
                      '<td>'+value.received_state+'</td>'+
                      '<td>'+in_qb+'</td>'+
                      '<td>'+closeout+'</td>'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>'+
                      '@perm("edit_po")'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'/edit" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>'+
                      '@endperm'+
                      '<td><a href="/purchaseorders/pdf/'+value.id+'" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>'+
                      '<td><a href="/purchaseorders/pdfDetail/'+value.id+'" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>'+
                    '</tr>';
                    incompleteorders.row.add($(newRow)).draw();
                  });
                  incompleteorders.searchPanes.rebuildPane();
                  drworking.close();
                },
                error: function (request, status, error) {
                  $.alert({
                      title: 'Encountered an error!',
                      content: request.responseText,
                      type: 'red',
                  });
                }
            });     
          }
          $('#daterange_incompleteorders').daterangepicker({
              startDate: start,
              endDate: end,
              maxDate: moment().subtract(0, 'days'),
              ranges: {
                  'Last 90 Days': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                  'This Year': [moment().startOf('year'), moment().endOf('year')],
                  'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
              }
          }, cb);
        });

      
      
        /*daterange_partialorders*/
      $(function() {
          var start = moment("{{$start}}");
          var end = moment("{{$end}}");
          var state = "Partial";
          var label = "Last 90 Days";
          $('#daterange_partialorders span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          function cb(start, end, label) {
            var from = start.format('YYYY-MM-DD');
            var to = end.format('YYYY-MM-DD');
            $('#daterange_partialorders span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            drworking.open();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax( {
                url: "{{ url('purchaseorders/getreceivedorders') }}",
                method: 'get',
                data: {
                    date_from: from,
                    date_to: to,
                    state: state
                },
                success: function ( data ) {
                  partialorders.clear().draw();
                  $.each(data[0], function (key,value) {
                    var in_qb = value.in_quickbooks.toUpperCase();
                    var closeout = (value.closeout==0) ? 'NO' : 'YES';
                    var newRow = 
                    '<tr>'+
                    '<td>'+value.id+'</td>'+
                    '<td>'+value.TxnDate+'</td>'+
                      '<td>'+value.RefNumber+'</td>'+
                      '<td>'+value.VendorName+'</td>'+
                      '<td>'+value.ShipToName+'</td>'+
                      '<td>'+value.received_state+'</td>'+
                      '<td>'+in_qb+'</td>'+
                      '<td>'+closeout+'</td>'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>'+
                      '@perm("edit_po")'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'/edit" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>'+
                      '@endperm'+
                      '<td><a href="/purchaseorders/pdf/'+value.id+'" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>'+
                      '<td><a href="/purchaseorders/pdfDetail/'+value.id+'" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>'+
                    '</tr>';
                    partialorders.row.add($(newRow)).draw();
                  });
                  partialorders.searchPanes.rebuildPane();
                  drworking.close();
                },
                error: function (request, status, error) {
                  $.alert({
                      title: 'Encountered an error!',
                      content: request.responseText,
                      type: 'red',
                  });
                }
            });     
          }
          $('#daterange_partialorders').daterangepicker({
              startDate: start,
              endDate: end,
              maxDate: moment().subtract(0, 'days'),
              ranges: {
                  'Last 90 Days': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                  'This Year': [moment().startOf('year'), moment().endOf('year')],
                  'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
              }
          }, cb);
        });

         /*daterange_pendingorders*/
        $(function() {
          var start = moment("{{$start}}");
          var end = moment("{{$end}}");
          var state = "Not Received"
          var label = "Last 90 Days";
          $('#daterange_pendingorders span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          function cb(start, end, label) {
            var from = start.format('YYYY-MM-DD');
            var to = end.format('YYYY-MM-DD');
            $('#daterange_pendingorders span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            drworking.open();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax( {
                url: "{{ url('purchaseorders/getreceivedorders') }}",
                method: 'get',
                data: {
                    date_from: from,
                    date_to: to,
                    state: state
                },
                success: function ( data ) {
                  pendingorders.clear().draw();
                  $.each(data[0], function (key,value) {
                    var in_qb = value.in_quickbooks.toUpperCase();
                    var closeout = (value.closeout==0) ? 'NO' : 'YES';
                    var newRow = 
                    '<tr>'+
                    '<td>'+value.id+'</td>'+
                    '<td>'+value.TxnDate+'</td>'+
                      '<td>'+value.RefNumber+'</td>'+
                      '<td>'+value.VendorName+'</td>'+
                      '<td>'+value.ShipToName+'</td>'+
                      '<td>'+value.received_state+'</td>'+
                      '<td>'+in_qb+'</td>'+
                      '<td>'+closeout+'</td>'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> Detail</a></td>'+
                      '@perm("edit_po")'+
                      '<td class="text-center"><a href="/purchaseorders/'+value.id+'/edit" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>'+
                      '@endperm'+
                      '<td><a href="/purchaseorders/pdf/'+value.id+'" class="btn btn-info btn-sm" data-toggle="tooltip" title="Download PDF"><i class="fa fa-file-pdf-o"></i> PDF</a></td>'+
                      '<td><a href="/purchaseorders/pdfDetail/'+value.id+'" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Download Detailed PDF"><i class="fa fa-file-pdf-o"></i> Detailed PDF</a></td>'+
                    '</tr>';
                    pendingorders.row.add($(newRow)).draw();
                  });
                  pendingorders.searchPanes.rebuildPane();
                  drworking.close();
                },
                error: function (request, status, error) {
                  $.alert({
                      title: 'Encountered an error!',
                      content: request.responseText,
                      type: 'red',
                  });
                }
            });     
          }
          $('#daterange_pendingorders').daterangepicker({
              startDate: start,
              endDate: end,
              maxDate: moment().subtract(0, 'days'),
              ranges: {
                  'Last 90 Days': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                  'This Year': [moment().startOf('year'), moment().endOf('year')],
                  'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
              }
          }, cb);
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