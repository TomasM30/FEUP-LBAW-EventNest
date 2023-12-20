<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\EventParticipant;

class PaypalController extends Controller
{

    public function index()
    {
        return view('paypal');
    }

    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
    
        $orderDetails = session('order');
        $amount = $orderDetails['amount'];
        $ticketType = $orderDetails['ticketType'];
    
        $totalCost = $ticketType->price * $amount;

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment.cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "description" => "Purchase of {$amount} tickets for {$ticketType->title}",
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $totalCost
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect($links['href']);
                }
            }
        } else {
            return redirect()
                ->route('create.payment')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }

    }


    public function paymentCancel()
    {
        return redirect()
            ->route('paypal')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }


    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $orderDetails = session('order');
            $event = Event::find($orderDetails['id_event']);

            $order = Order::create([
                'quantity' => $orderDetails['amount'],
                'id_user' => $orderDetails['id_user'],
            ]);

            for ($i = 0; $i < $orderDetails['amount']; $i++) {
                $ticket = Ticket::create([
                    'description' => 'Ticket for event ' . $event->title,
                    'id_order' => $order->id,
                    'id_ticket_type' => $orderDetails['ticketType']->id,
                    'price' => $orderDetails['ticketType']->price,
                    'date' => $event->date,
                ]);
            }

            EventParticipant::insert([
                'id_user' => $orderDetails['id_user'],
                'id_event' => $event->id,
            ]);

            $event->save();

            return redirect()
                ->route('events.details', ['id' => $orderDetails['id_event']])
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
            ->route('events.details', ['id' => $orderDetails['id_event']])
            ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
}
