<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
use App\Models\Invoice;
use App\Helper\SSLCommerz;
use App\Models\ProductCart;
use App\Models\ProductWish;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\InvoiceProduct;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            // Fetching user details
            $user_id = $request->header('id');
            $user_email = $request->header('email');
    
            if (!$user_id) {
                throw new Exception("User ID is missing from headers");
            }
    
            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';
    
            // Fetch customer profile
            $Profile = CustomerProfile::where('user_id', '=', $user_id)->first();
            if (!$Profile) {
                throw new Exception("Customer profile not found");
            }
    
            $cus_details = "Name: $Profile->cus_name, Address: $Profile->cus_add, City: $Profile->cus_city, Phone: $Profile->cus_phone";
            $ship_details = "Name: $Profile->ship_name, Address: $Profile->ship_add, City: $Profile->ship_city, Phone: $Profile->cus_phone";
    
            // Payable Calculation
            $total = 0;
            $cartList = ProductCart::where('customer_id', '=', $user_id)->get();
            foreach ($cartList as $cartItem) {
                $total += $cartItem->price;
            }
    
            $vat = ($total * 3) / 100;
            $payable = $total + $vat;
    
            // Create invoice
            $invoice = Invoice::create([
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'cus_details' => $cus_details,
                'ship_details' => $ship_details,
                'tran_id' => $tran_id,
                'delivery_status' => $delivery_status,
                'payment_status' => $payment_status,
                'customer_id' => $user_id, // Ensure this field is filled
            ]);
    
            $invoiceID = $invoice->id;
    
            // Create invoice products
            foreach ($cartList as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'product_id' => $EachProduct['product_id'],
                    'customer_id' => $user_id, // Ensure this field is filled
                    'quantity' => $EachProduct['quantity'],
                    'price' => $EachProduct['price'],
                ]);
             

            }
    
            // Initiate payment
            $paymentMethod = SSLCommerz::InitiatePayment($Profile, $payable, $tran_id, $user_email);
    
            DB::commit();
    
            return ResponseHelper::Out('success', [
                'paymentMethod' => $paymentMethod,
                'payable' => $payable,
                'vat' => $vat,
                'total' => $total
            ], 200);
    
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseHelper::Out('fail', $e->getMessage(), 200); // Return a more specific error message
        }
    }
    

    function InvoiceList(Request $request){
        $user_id=$request->header('id');
        return Invoice::where('customer_id',$user_id)->get();
    }

    function InvoiceProductList(Request $request){
        $user_id=$request->header('id');
        $invoice_id=$request->invoice_id;
        return InvoiceProduct::where(['customer_id'=>$user_id,'invoice_id'=>$invoice_id])->with('product')->get();
    }

    function PaymentSuccess(Request $request){
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
       
    }


    function PaymentCancel(Request $request){
        SSLCommerz::InitiateCancel($request->query('tran_id'));
        return redirect('/profile');
    }

    function PaymentFail(Request $request){
         SSLCommerz::InitiateFail($request->query('tran_id'));
        return redirect('/profile');
    }

    function PaymentIPN(Request $request){
        return SSLCommerz::InitiateIPN($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }

}
