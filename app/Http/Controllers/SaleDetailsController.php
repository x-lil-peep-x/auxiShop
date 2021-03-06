<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Spare;
use App\Models\StoreSpare;
use Illuminate\Http\Request;
use Exception;

class SaleDetailsController extends Controller
{

    /**
     * Display a listing of the sale details.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $saleDetailsObjects = SaleDetail::with('spare', 'sale')->paginate(25);

        return view('sale_details.index', compact('saleDetailsObjects'));
    }

    /**
     * Show the form for creating a new sale details.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $Spares = Spare::pluck('code', 'id')->all();
        $Sales = Sale::pluck('total_price', 'id')->all();

        return view('sale_details.create', compact('Spares', 'Sales'));
    }

    /**
     * Store a new sale details in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        try {

            $store_spare = StoreSpare::where('spare_id',$request->spare_id)
                ->where('store_id',$request->store_id)
                ->first();
            if ($store_spare->quantity >= $request->quantity) {
                //CREATING OR UPDATING SALE DETAILS
                $data = $this->getData($request);
                $saleDetail = SaleDetail::where('spare_id', $request->spare_id)
                    ->where('sale_id', $request->sale_id)
                    ->first();
                if($saleDetail){
                    $saleDetail->quantity += $request->quantity;
                    $saleDetail->discount += $request->discount;
                    $saleDetail->price += $request->price;
                    $saleDetail->real_price += $request->real_price;
                    $saleDetail->save();
                }else{
                    SaleDetail::create($data);
                }


                //UPDATING SPARE QUANTITY
                $spare = Spare::find($request->spare_id);
                $spare->quantity -= $request->quantity;
                $spare->save();
                //UPDATING SALE PRICE AND AMOUNT
                $sale = Sale::find($request->sale_id);
                $sale->total_price += $request->real_price;
                $sale->total_amount += $request->quantity;
                $sale->save();
                //UPDATING STORE SPARE QUANTITY
                $store_spare->quantity -= $request->quantity;
                $store_spare->save();
                return redirect()->route('sales.sale.show', [$request->sale_id])
                    ->with('success_message', 'Detalles de venta se agrego correctamente.');
            }
            return back()->withInput()
                ->withErrors(['error' => 'La cantidad maxima del producto es: '.$store_spare->quantity]);
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Error inesperado mientras se intentaba realizar tu peticion.']);
        }
    }

    /**
     * Display the specified sale details.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $saleDetails = SaleDetail::with('spare', 'sale')->findOrFail($id);

        return view('sale_details.show', compact('saleDetails'));
    }

    /**
     * Show the form for editing the specified sale details.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $saleDetails = SaleDetail::findOrFail($id);
        $Spares = Spare::pluck('code', 'id')->all();
        $Sales = Sale::pluck('total_price', 'id')->all();

        return view('sale_details.edit', compact('saleDetails', 'Spares', 'Sales'));
    }

    /**
     * Update the specified sale details in the storage.
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

            $saleDetails = SaleDetail::findOrFail($id);
            $saleDetails->update($data);

            return redirect()->route('sale_details.sale_details.index')
                ->with('success_message', 'Detalles de venta se actualizo correctamente.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Error inesperado mientras se intentaba realizar tu peticion.']);
        }
    }

    /**
     * Remove the specified sale details from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id, $spare_id, $store_id, $deatil_id)
    {

        try {
            $store_spare = StoreSpare::where('spare_id',$spare_id)
                ->where('store_id',$store_id)
                ->first();

            $saleDetails = SaleDetail::findOrFail($deatil_id);
            $sale = Sale::find($id);
            $sale->total_price -= $saleDetails->real_price;
            $sale->total_amount -= $saleDetails->quantity;
            $sale->save();

            $store_spare->quantity += $saleDetails->quantity;
            $store_spare->save();

            $saleDetails->delete();

            $spare = Spare::find($spare_id);
            $spare->update_quantity();
            return back()
                ->with('success_message', 'Detalles de venta se elimino correctamente.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Error inesperado mientras se intentaba realizar tu peticion.']);
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
            'spare_id' => 'required',
            'sale_id' => 'required',
            'price' => 'required|numeric|min:0.5|max:999999.99',
            'quantity' => 'required|numeric|min:0.5|max:2147483647',
            'discount' => 'nullable|numeric|min:0|max:999999.99',
            'real_price' => 'required|numeric|min:0.5|max:999999.99',
        ];

        $data = $request->validate($rules);

        return $data;
    }

}
