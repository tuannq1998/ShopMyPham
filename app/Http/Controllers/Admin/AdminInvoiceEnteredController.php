<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InvoiceEntered;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminInvoiceEnteredController extends Controller
{
    public function index()
    {
        $invoiceEntered = InvoiceEntered::with('suppliere','product')->orderByDesc('id')
            ->paginate(10);

        $viewData = [
            'invoiceEntered' => $invoiceEntered
        ];

        return view('admin.invoice_entered.index', $viewData);
    }

    public function create()
    {
    	$suppliere = Supplier::all();
    	$products = Product::select('id','pro_name','pro_price','pro_number')->orderByDesc('id')->get();

        return view('admin.invoice_entered.create', compact('suppliere','products'));
    }

    public function store(Request $request)
    {
        $data               = $request->except('_token');
        $data['created_at'] =  Carbon::now();
        $data['ie_total_money'] = $request->ie_number * $request->ie_money;
        InvoiceEntered::insert($data);
        return redirect()->back();
    }

    public function edit($id)
    {
        $invoiceEntered = InvoiceEntered::find($id);
		$suppliere = Supplier::all();
		$products = Product::select('id','pro_name','pro_price','pro_number')->orderByDesc('id')->get();
		return view('admin.invoice_entered.update', compact('suppliere','invoiceEntered','products'));
    }

    public function update(Request $request, $id)
    {
		$data               = $request->except('_token');
		$data['created_at'] =  Carbon::now();
		$data['ie_total_money'] = $request->ie_number * $request->ie_money;
		InvoiceEntered::find($id)->update($data);
        $data['updated_at'] = Carbon::now();
        return redirect()->back();
    }

    public function active($id)
    {
        $menu               = Menu::find($id);
        $menu->mn_status = ! $menu->mn_status;
        $menu->save();

        return redirect()->back();
    }

    public function hot($id)
    {
        $menu               = Menu::find($id);
        $menu->mn_hot = ! $menu->mn_hot;
        $menu->save();

        return redirect()->back();
    }

    public function delete($id)
    {
        $invoiceEntered = InvoiceEntered::find($id);
        if ($invoiceEntered) $invoiceEntered->delete();
        return redirect()->back();
    }
}
