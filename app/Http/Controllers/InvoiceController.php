<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function InvoicePage(){
        return view('pages.dashboard.invoice-page');
    }

    function SalePage(){
        return view('pages.dashboard.sale-page');
    }

    public function invoiceCreate(Request $request){
        DB::beginTransaction();
        try{
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');

            $customer_id = $request->input('customer_id');

            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'customer_id' => $customer_id,
                'user_id' => $user_id
            ]);

            $invoiceID = $invoice->id;

            $products = $request->input('products');

            foreach($products as $product){
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $product['product_id'],
                    'qty' => $product['qty'],
                    'sale_price' => $product['sale_price'],
                ]);
            }

            DB::commit();
            return 1;
        }
        catch(\Exception $e){
            DB::rollBack();
            return 0;
        }
    }


    public function invoiceSelect(Request $request){
        $user_id = $request->header('id');
        $invoices = Invoice::where('user_id',$user_id)->with('customer')->get();
        return $invoices;
    }


    public function InvoiceDetails(Request $request){
        $user_id = $request->header('id');
        $customerDetails = Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();
        $invoiceTotal = Invoice::where('user_id',$user_id)->where('id',$request->input('inv_id'))->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id',$request->input('inv_id'))->where('user_id',$user_id)->get();

        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct
        );
    }

    public function invoiceDelete(Request $request){
        DB::beginTransaction();
        try{
           $user_id = $request->header('id');
           InvoiceProduct::where('invoice_id',$request->input('inv_id'))->where('user_id',$user_id)->delete();
           Invoice::where('id',$request->input('inv_id'))->delete();
           DB::commit();
           return 1;
        }
        catch(\Exception $e){
            DB::rollBack();
            return 0;
        }
    }









}
