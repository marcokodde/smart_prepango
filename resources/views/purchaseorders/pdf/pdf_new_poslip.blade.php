<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    {{--<title></title>--}}
    <style>
        @page {
            margin: 20mm;
            @bottom-right {
                content: "Page " counter(page);
                font-size: 12px;
                font-family: Arial, sans-serif;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
        }

        div {
            padding: 5px;
        }

        div.page {
            page-break-inside: avoid;
        }

        table {
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            border-style: dotted;
            padding: 6px;
            text-align: left;
        }

        .column {
            float: left;
            width: 33.33%;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .titulo {
            text-align: center;
            padding: 1px;
            font-size: 10px;
            display: block;
        }

        #info td {
            height: 10px;
        }

        .column-container {
            display: flex;
        }

        .column {
            border: 1px solid black;
            width: 35%;
            box-sizing: border-box;
        }

        .column-container-tab {
            display: flex;
        }
        .no-border {
            border: none;
        }

        .no-border td {
            border: none;
            text-align: left;
        }
        .footer {
            margin-top: 10%;
            bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="titulo" style="margin-bottom: 2%; margin-top:-2%">
        <img src="{{ public_path('img/prepango.png') }}" alt="imagen" style="width: 190px; height: auto; float: left; margin-right: 20px;">
    </div>
    <p style="margin-top:1%; margin-bottom:1%">&nbsp;</p>
    <div class="column-container" style="margin-bottom: 8%">
        <div class="column">
            <p style="background: lavender; padding:.5%; margin-top:-.5%">Vendor: </p>
            <strong> {{ $po[0]->vendor ?? 'N/A' }} </strong>
        </div>
        <div class="column" style="margin-left: 5%">
            <p style="background: lavender; padding:.5%; margin-top:-.5%">Ship to: </p>
            <p>
                <strong>{{ $po[0]->ship_to ?? 'N/A' }}</strong><br>
                {{ $po[0]->address1 ?? '' }}<br>
                @if (isset($po[0]->address2))
                    {{ $po[0]->address2 ?? '' }}<br>
                @endif
                {{ $po[0]->address3 ?? '' }}
            </p>
        </div>
    </div>
    @if ($po[0]->address1 && $po[0]->address2 && $po[0]->address3)
        <p style="margin-bottom: 10%">&nbsp;&nbsp;</p>
    @elseif ($po[0]->address1 && $po[0]->address2 || $po[0]->address1 && $po[0]->address3)
        <p style="margin-bottom: 5%">&nbsp;&nbsp;</p>
    @else
        <p style="margin-bottom: 7%">&nbsp;&nbsp;</p>
    @endif
    <br><br><br><br>
    <div class="column-container-tab">
        <div class="column" style="background: lavender">
            <strong style="color: #504b4b;">PO #:  {{ $po[0]->po_number ?? 'N/A' }}</strong> 
        </div>
        <div class="column" style="margin-left: 5%">
            <strong style="color: #504b4b;">Ship Via: </strong> 
        </div>
    </div>
    <br><br><br>
    <p style="margin-top: 5%"></p>
    <div class="column-container-tab">
        <div class="column" style="background: lavender">
            <strong style="color: #504b4b;">Order Date: {{ $po[0]->order_date ?? 'N/A'}}</strong> 
        </div>
    </div>
    <br><br><br>
    <p style="margin-top: 5%"></p>
    <div class="column-container-tab">
        <div class="column" style="background: lavender">
            <strong style="color: #504b4b;">Shipping Terms: </strong> 
        </div>
    </div>

    <br><br><br>
    <p style="margin-top: 5%"></p>
    <div class="column-container-tab">
        <div class="column" style="background: lavender">
            <strong style="color: #504b4b;">Buyer: {{ $po[0]->buyer ?? 'N/A' }} </strong> 
        </div>
    </div>
    <br><br><br>
    <p style="margin-top: 5%"></p>
    <div class="column-container-tab">
        <div class="column" style="background: lavender">
            <strong style="color: #504b4b;">Buyer´s E-mail: {{ $po[0]->buyers_email ?? 'N/A'}}</strong> 
        </div>
    </div>
    <br><br>
    <table id="items" style="width:100%">
        <thead>
            <tr>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Item Number</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">UPC</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Product Description</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Unit Cost</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Units     Per    Case</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Total     Units</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Total     Case     Qty</th>
                <th style="white-space:nowrap;text-align:center;font-weight:bold; background-color: lavender;">Total  Order Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($po_detail as $detail)
                <tr>
                    <td>{{$detail->item_sku}}</td>
                    <td>{{$detail->upc}}</td>
                    <td>{{$detail->item}}</td>
                    <td style="text-align: right;">$ {{$detail->unit_cost}}</td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">{{ $detail->total_units}}</td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">$ {{ $detail->total_order_amount}}</td>
                </tr>
            @endforeach            
        </tbody>
        <tfoot>
            <tr>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td class="no-border"></td>
                <td style="background: lavender"><strong>Grand Total</strong></td>
                <td style="background: lavender;text-align:right"><strong>$ {{number_format($po_detail->sum('total_order_amount'), 2)}}</strong></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <div class="column-container">
        <div class="column">
            <div class="card" style="width: 75%">
                <p style="background: lavender;">Miscellaneous Information:</p>
            </div>
        </div>
        <div class="columna">
            <div class="thank-you-container">
                <p style="text-align: center">Thank You!</p>
                <p style="text-align: center">For invoicing or accounting related questions, please email <a href="mailto:ap@prepango.com">ap@prepango.com</a></p>
            </div>
        </div>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                if ($PAGE_COUNT > 1 && $PAGE_NUM > 1) {
                    $x = 765;
                    $y = 550;
                    $text = "$PAGE_NUM / $PAGE_COUNT";
                    $font = null;
                    $size = 9;
                    $color = array(0.5,0.5,0.5);
                    $word_space = 0.0;  //  default
                    $char_space = 0.0;  //  default
                    $angle = 0.0;   //  default
                    $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                }
            ');
        }
    </script>    
</body>
