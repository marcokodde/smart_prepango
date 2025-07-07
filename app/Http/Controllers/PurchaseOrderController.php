<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

use App\Models\QuickbooksPurchaseOrder;
use App\Models\QuickbooksPurchaseOrderDetail;

use Carbon\Carbon;
use DB;

class PurchaseOrderController extends Controller
{

    public function receivePurchaseOrder($id, Request $request)
    {

        $errors = new MessageBag;

        $po = QuickbooksPurchaseOrder::with('details')->find($id);

        if ($request->isMethod('POST')) {

            $input = $this->validate(request(), [
                'rows.*.received' => 'sometimes|nullable|date',
                'rows.*.received_quantity' => 'sometimes|nullable|integer',
            ]);
            $po = QuickbooksPurchaseOrder::find($id);

            if (isset($input['rows'])) {
                foreach ($input['rows'] as $rowId => $received) {
                    //skip blanks
                    if (!isset($received['received_quantity']) && isset($received['received'])) {
                        $errors->add('rows.' . $rowId . '.received_quantity', 'received_quantity is required');
                        continue;
                    }
                    if (!isset($received['received']) &&  isset($received['received_quantity'])) {
                        $errors->add('rows.' . $rowId . '.received', 'received date: is required');
                        continue;
                        //->withErrors($errors);
                    }

                    $po_detail = QuickbooksPurchaseOrderDetail::find($rowId);

                    if ($received['received_quantity'] == 0 or $received['received_quantity'] >= $po_detail->Quantity) {
                        $po_detail->received = $received['received'];
                        $po_detail->received_quantity = $received['received_quantity'];
                        $po_detail->received_by = Auth::id();
                        $po_detail->save();
                    } elseif ($received['received_quantity'] > 0 and $received['received_quantity'] < $po_detail->Quantity) {
                        $partial = $po_detail->replicate();
                        $partial->Quantity = $received['received_quantity'];
                        $partial->received = $received['received'];
                        $partial->received_quantity = $received['received_quantity'];
                        $partial->received_by = Auth::id();
                        $partial->save();
                        $po_detail->Quantity = $po_detail->Quantity - $received['received_quantity'];
                        $po_detail->save();
                    }
                }
            }
            $data = ['po' => $po];
            return view('purchaseorders.receive')->with($data)->withErrors($errors);


            /* $whs_items = Dt_whs_item::whereIn('id', $po->details->pluck('whs_item_id')->toArray())->get();
            info('dispatched whsitm updated', [$whs_items]);
            ProcessWhsItems::dispatch($whs_items); */
        }
        $data = ['po' => $po];
        return view('purchaseorders.receive')->with($data);
    }

    public function edit($id)
    {
        $po = Quickbooks_purchase_order::with('details', 'notes', 'invoices')->find($id);
        $vendors = Quickbooks_vendor::where('isActive', 'true')->orderBy('name')->get();

        if ($po->Vendor_ListID == 'mexico') {
            $vendor = Quickbooks_vendor::where('Name', $po->VendorName)->first();
        } else {
            $vendor = Quickbooks_vendor::where('ListID', $po->Vendor_ListID)->first();
        }
        $shiptos = Ct_warehouses::whereNotNull('ListId')->where('status', 1)->orderBy('Name')->get();
        $shipto = Ct_warehouses::where('id', $po->ShipToID)->first();

        $reorders = DB::table('vw_vendor_whs_items_reorder')
            ->where('vendor_id', $vendor->id)
            ->where('warehouse_id', $po->ShipToID)
            ->get();

        //$order_details = Quickbooks_purchase_order_detail::where('purchase_order_id', $po->id)->get();

        $products = DB::table('vw_whs_items')->where('warehouse_id', $shipto->id)->whereIn('status', ['active', 'clearance'])->orderBy('item')->get();

        $data = [
            'po'        => $po,
            'vendors'   => $vendors,
            'vendor'    => $vendor,
            'shiptos'   => $shiptos,
            'shipto'    => $shipto,
            'products'  => $products,
            'reorders'  => $reorders
        ];
        return view('purchaseorders.edit')->with($data);
    }

    public function destroy($id)
    {
        QuickbooksPurchaseOrder::destroy($id);
        info('softDelete qb purchaseOrder:', [$id]);
    }
}
