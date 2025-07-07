<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <style>
        div.page:last-of-type {
            page-break-after: auto !important;
        }

        div {
            padding: 10px;
        }

        div.page {
            page-break-after: always;
            page-break-inside: avoid;
        }

        span.cls_004 {
            font-family: "Verdana", Geneva, sans-serif;
            font-size: 40px;
            color: rgb(180, 180, 180);
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
        }

        span.cls_005 {
            font-family: "Verdana", Geneva, sans-serif;
            font-size: 22px;
            color: rgb(50, 50, 50);
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
        }

        span.cls_002 {
            font-family: "Verdana", Geneva, sans-serif;
            font-size: 14px;
            color: rgb(50, 50, 50);
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            border: 1px solid grey;
        }

        table,
        th,
        td {
            text-align: center;
            border: 1px solid grey;
            font-family: "Verdana", Geneva, sans-serif;
            font-size: 14px;
            padding: 4px;
            color: rgb(50, 50, 50);
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
        }
    </style>
</head>
{{--@foreach ($po->details->chunk(15) as $chunk)--}}
@if(str_contains($po->ShipToName, 'LEGO'))
    @foreach ($po->details->sortBy('sku')->chunk(15) as $chunk)
        <div class="page">
            <div style="position:absolute;left:50%;margin-left:-400px;top:0px;width:750px;height:1050px;">
                <div style="position:absolute;left:57px;top:35px"><span
                        class="cls_005"><strong>{{ $pdfdata->company_name }}</strong></span></div>
                <div style="position:absolute;left:350px;top:25px"><span class="cls_004">{{ $pdfdata->document_title }}</span>
                </div>

                <div style="position:absolute;left:57px;top:90px"><span class="cls_002"><strong>Vendor: </strong></span></div>
                <div style="position:absolute;left:120px;top:90px"><span
                        class="cls_002"><mark>{{ $po->VendorName }}</mark></span></div>
                <div style="position:absolute;left:57px;top:109px">
                    <span class="cls_002"><strong>Ship To: </strong>
                    </span>
                </div>
                <div style="position:absolute;left:120px;top:109px">
                    <span class="cls_002">
                        <mark>{{ $poShipTo[0]->ship_to ?? 'N/A' }}, {{ $poShipTo[0]->attn ?? '' }}</mark>
                        <br>
                        <mark>
                            {{ $poShipTo[0]->address1 ?? '' }}
                            @if (isset($poShipTo[0]->address2))
                                {{ $poShipTo[0]->address2 ?? '' }}
                            @endif
                            {{ $poShipTo[0]->address3 ?? '' }}
                        </mark>
                    </span>
                </div>
                <div style="position:absolute;left:57px;top:157px"><span class="cls_002"><strong>Date: </strong></span>
                </div>
                <div style="position:absolute;left:120px;top:159px">
                    <span class="cls_002"><mark>{{ $po->TxnDate }}</mark></span>
                </div>
                {{-- <div style="position:absolute;left:57px;top:155px"><span class="cls_002"><mark>{{$pdfdata->header_line_one}}</mark><br><mark>{{$pdfdata->header_line_two}}</mark><br>@if (isset($pdfdata->header_line_three))<mark>{{$pdfdata->header_line_three}} {{$pdfdata->header_contact}}</mark>@endif</span></div> --}}
                {{-- <div style="position:absolute;left:57px;top:155px"><span class="cls_002"><mark>{{$pdfdata->header_line_one}}</mark><br><mark>{{$pdfdata->header_line_two}}</mark></span></div> --}}
                <div style="position:absolute;left:57px;top:175px"><span
                        class="cls_002"><mark>{{ $pdfdata->header_line_one }} {{ $pdfdata->header_line_two }}</mark></span>
                </div>

                <div style="position:absolute;left:57px;top:205px;">
                    <table style="width:675px" id="infoTable">
                        <thead>
                            <tr>
                                <th style="text-align:center">PO ID</th>
                                <th style="text-align:center">PO Number</th>
                                <th style="text-align:center">PO Date</th>
                                {{-- <th style="text-align:center">Vendor</th> --}}
                                {{-- <th style="text-align:center">Shipment</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:center">{{ $po->id }}</td>
                                <td style="text-align:center">{{ $po->RefNumber }}</td>
                                <td style="text-align:center">{{ $po->TxnDate }}</td>
                                {{-- <td style="text-align:center">{{$po->VendorName}}</td> --}}
                                {{-- <td style="text-align:center">{{$shipment->machine->name}}-S{{$shipment->sequence}}</td> --}}
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="position:absolute;left:57px;top:275px">
                    <table style="width:675px" id="infoTable">
                        <thead>
                            <tr>
                                <th>SKU</th>                            
                                <th>Description</th>
                                <th>Quantity Ordered</th>
                                <th>Quantity Received</th>
                                <th>Date Received</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach ($chunk->sortBy('item') as $item)--}}
                            @foreach ($chunk as $item)
                                @if ($item->Quantity > 0)
                                    <tr>
                                        <td>{{ $item->sku }}</td>                                    
                                        <td>{{ $item->item }}</td>
                                        <td>{{ $item->Quantity }}</td>
                                        <td>{{ $item->received_quantity ?? '' }}</td>
                                        <td>{{ $item->received ?? '' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="position:absolute;left:175px;top:900px">
                    <span class="cls_002">{{ $pdfdata->footer_line_one }}</span>
                </div>
                <div style="position:absolute;left:360px;top:925px">
                    <span class="cls_002">{{ $pdfdata->footer_line_two }}</span>
                </div>
                <div style="position:absolute;left:200px;top:950px">
                    <span class="cls_002">{{ $pdfdata->footer_line_three }} {{ $pdfdata->footer_contact }}</span>
                </div>
            </div>
        </div>
    @endforeach
@else
    @foreach ($po->details->sortBy('item')->chunk(15) as $chunk)
        <div class="page">
            <div style="position:absolute;left:50%;margin-left:-400px;top:0px;width:750px;height:1050px;">
                <div style="position:absolute;left:57px;top:35px"><span
                        class="cls_005"><strong>{{ $pdfdata->company_name }}</strong></span></div>
                <div style="position:absolute;left:350px;top:25px"><span class="cls_004">{{ $pdfdata->document_title }}</span>
                </div>

                <div style="position:absolute;left:57px;top:90px"><span class="cls_002"><strong>Vendor: </strong></span></div>
                <div style="position:absolute;left:120px;top:90px"><span
                    class="cls_002"><mark>{{ $po->VendorName }}</mark></span>
                </div>
                <div style="position:absolute;left:57px;top:109px">
                    <span class="cls_002"><strong>Ship To: </strong>
                    </span>
                </div>
                <div style="position:absolute;left:120px;top:109px">
                    <span class="cls_002">
                        <mark>{{ $poShipTo[0]->ship_to ?? 'N/A' }}, {{ $poShipTo[0]->attn ?? '' }}</mark>
                        <br>
                        <mark>
                            {{ $poShipTo[0]->address1 ?? '' }}
                            @if (isset($poShipTo[0]->address2))
                                {{ $poShipTo[0]->address2 ?? '' }}
                            @endif
                            {{ $poShipTo[0]->address3 ?? '' }}
                        </mark>
                    </span>
                </div>
                <div style="position:absolute;left:57px;top:157px">
                    <span class="cls_002"><strong>Date: </strong></span>
                </div>
                <div style="position:absolute;left:120px;top:159px">
                    <span class="cls_002"><mark>{{ $po->TxnDate }}</mark></span>
                </div>
                {{-- <div style="position:absolute;left:57px;top:155px"><span class="cls_002"><mark>{{$pdfdata->header_line_one}}</mark><br><mark>{{$pdfdata->header_line_two}}</mark><br>@if (isset($pdfdata->header_line_three))<mark>{{$pdfdata->header_line_three}} {{$pdfdata->header_contact}}</mark>@endif</span></div> --}}
                {{-- <div style="position:absolute;left:57px;top:155px"><span class="cls_002"><mark>{{$pdfdata->header_line_one}}</mark><br><mark>{{$pdfdata->header_line_two}}</mark></span></div> --}}
                <div style="position:absolute;left:57px;top:175px"><span
                        class="cls_002"><mark>{{ $pdfdata->header_line_one }} {{ $pdfdata->header_line_two }}</mark></span>
                </div>

                <div style="position:absolute;left:57px;top:205px;">
                    <table style="width:675px" id="infoTable">
                        <thead>
                            <tr>
                                <th style="text-align:center">PO ID</th>
                                <th style="text-align:center">PO Number</th>
                                <th style="text-align:center">PO Date</th>
                                {{-- <th style="text-align:center">Vendor</th> --}}
                                {{-- <th style="text-align:center">Shipment</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:center">{{ $po->id }}</td>
                                <td style="text-align:center">{{ $po->RefNumber }}</td>
                                <td style="text-align:center">{{ $po->TxnDate }}</td>
                                {{-- <td style="text-align:center">{{$po->VendorName}}</td> --}}
                                {{-- <td style="text-align:center">{{$shipment->machine->name}}-S{{$shipment->sequence}}</td> --}}
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="position:absolute;left:57px;top:275px">
                    <table style="width:675px" id="infoTable">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity Ordered</th>
                                <th>Quantity Received</th>
                                <th>Date Received</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--@foreach ($chunk->sortBy('item') as $item)--}}
                            @foreach ($chunk as $item)
                            {{--@foreach ($chunk->quickbooks_item->sortBy('ManufacturerPartNumber') as $item)--}}
                                @if ($item->Quantity > 0)
                                    <tr>
                                        <td>{{ $item->item }}</td>
                                        <td>{{ $item->Quantity }}</td>
                                        <td>{{ $item->received_quantity ?? '' }}</td>
                                        <td>{{ $item->received ?? '' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="position:absolute;left:175px;top:900px">
                    <span class="cls_002">{{ $pdfdata->footer_line_one }}</span>
                </div>
                <div style="position:absolute;left:360px;top:925px">
                    <span class="cls_002">{{ $pdfdata->footer_line_two }}</span>
                </div>
                <div style="position:absolute;left:200px;top:950px">
                    <span class="cls_002">{{ $pdfdata->footer_line_three }} {{ $pdfdata->footer_contact }}</span>
                </div>
            </div>
        </div>
    @endforeach
@endif
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
			if ($PAGE_COUNT > 1 && $PAGE_NUM > 1) {
				$x = 522;
				$y = 790;
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