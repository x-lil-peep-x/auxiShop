<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseSpare;
use App\Models\Spare;
use Illuminate\Http\Request;
use Exception;

class PurchaseSparesController extends Controller
{

    /**
     * Display a listing of the purchase spares.
     *
     * @return Illuminate\View\View
     */
    public function index($id)
    {
        $purchaseSpares = PurchaseSpare::all()->where('purchase_id', $id);
        return view('purchase_spares.index', compact('purchaseSpares'));
    }

    /**
     * Show the form for creating a new purchase spare.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $Purchases = Purchase::pluck('contact','id')->all();
        $Spares = Spare::pluck('code','id')->all();

        return view('purchase_spares.create', compact('Purchases','Spares'));
    }

    /**
     * Store a new purchase spare in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request, $id)
    {
        try {

            $data = $this->getData($request);

            $purchase = Purchase::find($id);
            $purchase->total_price += $request->price;
            $purchase->save();

            $spare = Spare::find($request->spare_id);
            $spare->quantity += $request->quantity;
            $spare->save();

            PurchaseSpare::create($data);

            return redirect()->route('purchases.purchase.show', $id)
                ->with('success_message', 'Purchase Spare was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified purchase spare.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $purchaseSpare = PurchaseSpare::with('purchase','spare')->findOrFail($id);

        return view('purchase_spares.show', compact('purchaseSpare'));
    }

    /**
     * Show the form for editing the specified purchase spare.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $purchaseSpare = PurchaseSpare::findOrFail($id);
        $Purchases = Purchase::pluck('contact','id')->all();
        $Spares = Spare::pluck('code','id')->all();

        return view('purchase_spares.edit', compact('purchaseSpare','Purchases','Spares'));
    }

    /**
     * Update the specified purchase spare in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $purchaseSpare = PurchaseSpare::findOrFail($id);
            $purchaseSpare->update($data);

            return redirect()->route('purchase_spares.purchase_spare.index')
                ->with('success_message', 'Purchase Spare was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified purchase spare from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($purchase_id, $purchase_spare_id)
    {
        try {
            $purchase = Purchase::find($purchase_id);

            $purchaseSpare = PurchaseSpare::findOrFail($purchase_spare_id);
            $purchase->total_price -= $purchaseSpare->price;
            $purchase->save();

            $spare = Spare::find($purchaseSpare->spare_id);
            $spare->quantity -= $purchaseSpare->quantity;
            $spare->save();

            $purchaseSpare->delete();

            return redirect()->route('purchase_spares.purchase_spare.index')
                ->with('success_message', 'Purchase Spare was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'purchase_id' => 'nullable',
            'spare_id' => 'nullable',
            'unit_price' => 'nullable|numeric|min:-999999.99|max:999999.99',
            'price' => 'nullable|numeric|min:-999999.99|max:999999.99',
            'quantity' => 'nullable|numeric|min:-2147483648|max:2147483647',
        ];

        $data = $request->validate($rules);


        return $data;
    }

}