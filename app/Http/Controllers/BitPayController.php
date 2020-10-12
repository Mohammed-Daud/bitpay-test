<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Listeners\ProcessBitpayWebhook;
use Illuminate\Support\Facades\Redirect;
use Vrajroham\LaravelBitpay\LaravelBitpay;


class BitPayController extends Controller
{
    public function index(Request $request)
    {
        return view('payment_form');
    }

    public function pay(Request $request)
    {
        // Create instance of invoice
        $invoice = LaravelBitpay::Invoice();
        
        // Set item details (Only 1 item)
        $invoice->setItemDesc('test_item');
        $invoice->setItemCode('sku-1');
        $invoice->setPrice($request->input('amount'));

        // Please make sure you provide unique orderid for each invoice
        $invoice->setOrderId(rand()); // E.g. Your order number

        

        // Create Buyer Instance
        $buyer = LaravelBitpay::Buyer();
        $buyer->setName('test buyer');
        $buyer->setEmail('daud.csbt@gmail.com');
        $buyer->setAddress1('India');
        $buyer->setNotify(true);

        // Add buyer to invoice
        $invoice->setBuyer($buyer);

        // Set currency
        $invoice->setCurrency('USD');

        

        // Set redirect url to get back after completing the payment. GET Request
        $invoice->setRedirectURL(route('bitpay-redirect-back'));

        

        // Optional config. setNotificationUrl()
        // By default, package handles webhooks and dispatches BitpayWebhookReceived event as described above.
        // If you want to handle webhooks your way, you can provide url below. 
        // If handled manually, BitpayWebhookReceived event will not be dispatched.    
        // $invoice->setNotificationUrl('handle_webhooks');

        // Create invoice on bitpay server.
        $invoice = LaravelBitpay::createInvoice($invoice);

        // You can save invoice ID from server, for your your reference
        $invoiceId = $invoice->getId();

        $paymentUrl = $invoice->getUrl();
        // Redirect user to following URL for payment approval.
        return Redirect::to($paymentUrl);
    }

    public function handleResponse(Request $request){
        return view('thankyou');
    }

    
}
