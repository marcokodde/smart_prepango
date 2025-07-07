<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QuickbooksPurchaseOrder;
use App\Models\QuickbooksPurchaseOrderDetail;


class PurchaseOrderReceive extends Component
{
    public $poId;
    public $po;
    public string $barcodeInput = '';
    public array $rows = [];

    public int $currentIndex = -1;
    public int $manualQty = 0;
    public bool $showModal = false;

    public function mount($poId)
    {
        $this->poId = $poId;
        $this->po = QuickbooksPurchaseOrder::with('details')->findOrFail($poId);

        $this->rows = $this->po->details->map(function ($item) {
            return [
                'id' => $item->id,
                'barcode' => $item->id,
                'expected_quantity' => $item->Quantity,
                'received_quantity' => $item->received_quantity ?? 0,
                'complete' => false,
                'item_name' => $item->item,
                'rate' => $item->Rate,
                'date' => $item->received ?? null,
                'sku' => $item->whs_item->products[0]->item_sku ?? null,
            ];
        })->toArray();
    }

    public function render() {
        return view('livewire.purchase-order-receive');
    }

    public function processBarcode()
    {
        $code = trim($this->barcodeInput);
        $this->barcodeInput = '';

        $index = collect($this->rows)->search(fn ($row) => (string) $row['barcode'] == $code);

        if ($index === false) {
            $this->dispatch('barcode-not-found');
            return;
        }

        $row = $this->rows[$index];

        if ($row['complete']) {
            $this->dispatch('barcode-complete');
            return;
        }

        if ($row['received_quantity'] >= $row['expected_quantity']) {
            $this->dispatch('barcode-maxed');
            return;
        }

        $this->rows[$index]['received_quantity'] += 1;
        $this->rows[$index]['date'] = now()->toDateString();

        if ($this->rows[$index]['received_quantity'] >= $row['expected_quantity']) {
            $this->rows[$index]['complete'] = true;
        }
    }

    public function addQtyManual($index)
    {
        $row = $this->rows[$index] ?? null;
        if (!$row) return;

        $typedValue = (string) $row['received_quantity'];
        $barcode = (string) $row['barcode'];

        if ($typedValue === $barcode) {
            $this->currentIndex = $index;
            $this->manualQty = 0;
            $this->showModal = true;
            $this->rows[$index]['received_quantity'] = 0;
        } else {
            $this->rows[$index]['received_quantity'] = 0;
            $this->dispatch('noty', msg: 'NOT BARCODE FOUND');
            return;
        }

    }

    public function confirmQty()
    {
        if ($this->currentIndex < 0) return;

        $qty = max(0, (int) $this->manualQty);
        $expected = $this->rows[$this->currentIndex]['expected_quantity'];

        $this->rows[$this->currentIndex]['received_quantity'] = min($qty, $expected);
        $this->rows[$this->currentIndex]['scanned_count'] = min($qty, $expected);
        $this->rows[$this->currentIndex]['date'] = now()->toDateString();
        $this->saveRow($this->currentIndex);
        $this->showModal = false;
    }

    public function saveRow($index)
    {
        $row = $this->rows[$index] ?? null;
        if (!$row || !isset($row['id'])) return;
        $model = QuickbooksPurchaseOrderDetail::find($row['id']);
        if ($model) {
            $model->received_quantity = $row['received_quantity'];
            $model->received = $row['date'];
            $model->save();
        }
    }
}
