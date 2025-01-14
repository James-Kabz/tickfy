<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
          // public function show(Request $request, Event $event)
          // {
          //           $ticketDetails = $request->input('ticket_types', []);
          //           // $tickets = $request->
          //           $grandTotal = $request->input('grand_total', 0);

          //           return view('payment', compact('event', 'ticketDetails', 'grandTotal'));
          // }

          public function token()
          {
                    $consumerKey = 'b7KnMM6MLAeIynsJoZ38WjJRXACqNXGEJQdKGJwydf7lKRW3';
                    $consumerSecret = 'TIC42PSb5qsLBGQ7uuWhQKxF1uRsHeCfPajKKYUWM3h2qOb205qMGWG21cpTX3j2';
                    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

                    $response = Http::withBasicAuth($consumerKey, $consumerSecret)->get($url);


                    if ($response->failed()) {
                              throw new Exception('Failed to obtain access token: ' . $response->body());
                    }

                    return $response->json()['access_token'];
          }

          public function initiateStkPush()
          {
                    $accessToken = $this->token();
                    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

                    $PhoneNumber = 254740289578;
                    $Amount = 1;
                    $AccountReference = 'Tickfy';
                    $TransactionDesc = 'Payment for Event Ticket';
                    $response = Http::withToken($accessToken)->post($url, [
                              'BusinessShortCode' => env('MPESA_SHORTCODE'),
                              'Password' => base64_encode(env('MPESA_SHORTCODE') . env('MPESA_PASSKEY') . now()->format('YmdHis')),
                              'Timestamp' => now()->format('YmdHis'),
                              'TransactionType' => 'CustomerPayBillOnline',
                              'Amount' => $Amount,
                              'PartyA' => $PhoneNumber,
                              'PartyB' => env('MPESA_SHORTCODE'),
                              'PhoneNumber' => $PhoneNumber,
                              'CallBackURL' => 'https://a1ce-197-232-1-50.ngrok-free.app/payments/stkcallback',
                              'AccountReference' => $AccountReference,
                              'TransactionDesc' => $TransactionDesc,
                    ]);

                    if ($response->failed()) {
                              throw new Exception('Failed to obtain access token: ' . $response->body());
                    }

                    return $response->json();
          }

          public function stkCallback()
          {
                    $data = file_get_contents('php://input');
                    $callbackData = json_decode($data, true);
                    $stkCallback = $callbackData['Body']['stkCallback'];

                    Storage::disk('local')->put('stk_callback.json', json_encode($stkCallback, JSON_PRETTY_PRINT));
          }

          public function show(Request $request, Event $event)
          {
                    $ticketDetails = $request->input('ticket_types', []);
                    $grandTotal = 0;

                    // Calculate the grand total from ticket details
                    foreach ($ticketDetails as $ticketTypeId => $details) {
                              $ticket = $event->ticketTypes->find($ticketTypeId);
                              if ($ticket) {
                                        $price = $ticket->price; // Assuming each ticket type has a 'price' attribute
                                        $quantity = $details['quantity'] ?? 0;
                                        $grandTotal += $price * $quantity;
                              }
                    }

                    // Store ticket and payment details in session
                    session([
                              'ticketDetails' => [
                                        'name' => $request->input('name'),
                                        'phone_number' => $request->input('phone_number'),
                                        'email' => $request->input('email'),
                                        'tickets' => $ticketDetails, // Include ticket details for later use
                              ],
                              'grandTotal' => $grandTotal,
                    ]);

                    return view('payment', [
                              'event' => $event,
                              'ticketDetails' => $ticketDetails,
                              'grandTotal' => $grandTotal,
                    ]);
          }
}
