@extends('layouts.app')

@section('content')
@include('purchaseorders.sidebar')
<style>
    .select2-container--default .select2-selection--single{
        background-color: lavender;
        height: 30px !important;
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
    .select2-selection__placeholder {
        font-weight: bold;
    }
</style>
<div class="col-xs-12 col-sm-9">
    <h2>Create Purchase Order</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST"  id="purchaseOrderForm">
        {{ csrf_field() }}
        <div class="form-group row {{ $errors->has('date') ? 'has-error' : '' }}">
            <label for="date" class="col-sm-2 col-form-label">Date:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="form-group row {{ $errors->has('vendor') ? 'has-error' : '' }}">
            <label for="vendor" class="col-sm-2 col-form-label">Select Vendor:</label>
            <div class="col-sm-4">
                <select id="vendor" class="form-control vendor" name="vendor">
                    <option></option>
                    @foreach ($vendors as $vendor)
                    <option value="{{$vendor->id}}">{{$vendor->Name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row {{ $errors->has('shipto') ? 'has-error' : '' }}">
            <label for="shipto" class="col-sm-2 col-form-label">Select Ship To:</label>
            <div class="col-sm-4">
                <select id="shipto" class="form-control shipto" name="shipto">
                    <option></option>
                    @foreach ($sites as $site)
                    <option value="{{$site->id}}">{{$site->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="closeout">Closeout Purchase Order:</label>
            <div class="col-sm-4">
                <input type="checkbox" value="0" id="closeout" name="closeout">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; float: right;">
                    <a href="{{url()->previous()}}" class="btn btn-sm btn-danger"style="margin-left:10px"><i class="fa fa-chevron-circle-left"></i> Back</a>
                    <button id="createPO" name="createPO" class="btn btn-sm btn-success" style="margin-left:10px"><i class="fa fa-th-list"></i> Start Purchase Order</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.vendor').select2({placeholder: '   Select Vendor'});
        $('.shipto').select2({placeholder: '   Select Ship To'});
        $(":checkbox").change(function(){
            $(this).val($(this).is(":checked") ? 1 : 0);
        });
    });

    document.getElementById('createPO').addEventListener('click', function () {
        sendForm('{{ route('purchaseorders.store') }}');
    });

    function sendForm(action) {
        event.preventDefault();
        var form = document.getElementById('purchaseOrderForm');
        form.action = action;
        form.submit();
    }

</script>
@endsection