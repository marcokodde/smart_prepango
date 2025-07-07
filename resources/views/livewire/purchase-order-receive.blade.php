<div>
    <div class="row">
        <div class="col-xs-12 col-sm-9">
            <h2>Update Purchase Orders</h2>
            <h2>Date:{{$po->TxnDate}}</h2>

            <button type="button" class="btn btn-success" style="float:right"><i class="fa fa-save"></i>Receive</button>

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

            <br>
            <div class="row">
                <div class="col-sm-4">
                    <label for="barcode_input">Scan Barcode:</label>
                    <input
                        wire:model.defer="barcodeInput"
                        wire:keydown.enter="processBarcode"
                        type="text"
                        placeholder="Scan barcode..."
                        class="form-control"
                        autofocus
                    />
                </div>
            </div>
            <br>

            <table id="products_table" class="table order-list table-hover table-condensed table-striped table-bordered">
                <thead>
                    <tr style="background-color:Lavender" >
                        <th>Quantity Ordered</th>
                        <th>Quantity Received</th>
                        <th>Date Received</th>
                        <th>Description</th>
                        <th>Rate</th>
                        <th>Scanned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $index => $item)
                      <tr class="{{ $item['complete'] ? 'table-success' : '' }}">
                          <td>{{ $item['expected_quantity'] }}</td>
                          <td>
                            <input hidden value="{{ $item['barcode'] }}" />
                            <input  type="number"
                            wire:model.defer="rows.{{ $index }}.received_quantity"
                            wire:keydown.enter="addQtyManual({{ $index }})"
                            class="form-control"
                            />
                          </td>
                          <td>
                              <input type="date" wire:model.defer="rows.{{ $index }}.date" class="form-control" />
                          </td>
                          <td>{{ $item['sku'] ? $item['sku'].' - ' : '' }}{{ $item['item_name'] }}</td>
                          <td>{{ $item['rate'] }}</td>
                          <td>{{ $item['received_quantity'] }}</td>
                      </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($showModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Cantidad manual para:
                            {{ $rows[$currentIndex]['item_name'] ?? 'Producto' }}</h5>
                    </div>
                    <div class="modal-body">
                        <label>Código de barras</label>
                        <input type="text" class="form-control" value="{{ $rows[$currentIndex]['barcode'] }}" readonly>

                        <label class="mt-2">Cantidad:</label>
                        <input type="number" min="0" wire:model="manualQty" class="form-control" />
                    </div>
                    <div class="modal-footer">
                        <button wire:click="confirmQty" class="btn btn-primary">Guardar</button>
                        <button wire:click="$set('showModal', false)" class="btn btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>


