<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Event;
use App\Models\StkRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Facades\Log;

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

                    try {
                              $response = Http::withToken($accessToken)->post($url, [
                                        'BusinessShortCode' => env('MPESA_SHORTCODE'),
                                        'Password' => base64_encode(env('MPESA_SHORTCODE') . env('MPESA_PASSKEY') . now()->format('YmdHis')),
                                        'Timestamp' => now()->format('YmdHis'),
                                        'TransactionType' => 'CustomerPayBillOnline',
                                        'Amount' => $Amount,
                                        'PartyA' => $PhoneNumber,
                                        'PartyB' => env('MPESA_SHORTCODE'),
                                        'PhoneNumber' => $PhoneNumber,
                                        'CallBackURL' => 'https://70ec-197-232-1-50.ngrok-free.app/payments/stkcallback',
                                        'AccountReference' => $AccountReference,
                                        'TransactionDesc' => $TransactionDesc,
                              ]);
                    } catch (Throwable $e) {
                              return $e->getMessage();
                    }

                    if ($response->failed()) {
                              throw new Exception('Failed to obtain access token: ' . $response->body());
                    }

                    // return $response->json();

                    $res = json_decode($response);
                    $ResponseCode = $res->ResponseCode;
                    if ($ResponseCode == 0) {
                              $MerchantRequestID = $res->MerchantRequestID;
                              $CheckoutRequestID = $res->CheckoutRequestID;
                              $CustomerMessage = $res->CustomerMessage;

                              // save to dtabase
                              $payment = new StkRequest;
                              $payment->phone = $PhoneNumber;
                              $payment->amount = $Amount;
                              $payment->reference = $AccountReference;
                              $payment->description = $TransactionDesc;
                              $payment->MerchantRequestID = $MerchantRequestID;
                              $payment->CheckoutRequestID = $CheckoutRequestID;
                              $payment->status = 'Requested';

                              $payment->save();


                              return $CustomerMessage;
                    }
          }

          public function stkCallback(Request $request): JsonResponse
          {
                    // Log the received callback data for debugging
                    Log::channel('mpesa')->info('Received STK Callback data: ' . json_encode($request->all()));

                    // Extract callback data
                    $callbackData = $request->input('Body.stkCallback', []);

                    // Extract necessary fields
                    $merchantRequestId = $callbackData['MerchantRequestID'] ?? null;
                    $checkoutRequestId = $callbackData['CheckoutRequestID'] ?? null;
                    $resultCode = $callbackData['ResultCode'] ?? null;
                    $resultDesc = $callbackData['ResultDesc'] ?? null;

                    // Handle failed payment callback
                    if ($resultCode !== 0) {
                              Log::channel('mpesa')->error('STK Callback failed with ResultCode: ' . $resultCode . ' - ' . $resultDesc);

                              // Update payment status to failed in the database
                              $payment = StkRequest::where('CheckoutRequestID', $checkoutRequestId)->first();
                              if ($payment) {
                                        $payment->status = 'failed';
                                        $payment->ResultDesc = $resultDesc;
                                        $payment->save();
                              }

                              return response()->json([
                                        'status' => 'error',
                                        'message' => $resultDesc
                              ], 500);
                    }

                    // Handle successful payment callback
                    $callbackMetadata = $callbackData['CallbackMetadata']['Item'] ?? [];
                    $amount = null;
                    $transactionDate = null;
                    $mpesaReceiptNumber = null;
                    $msisdn = null;

                    foreach ($callbackMetadata as $item) {
                              switch ($item['Name']) {
                                        case 'Amount':
                                                  $amount = $item['Value'];
                                                  break;
                                        case 'MpesaReceiptNumber':
                                                  $mpesaReceiptNumber = $item['Value'];
                                                  break;
                                        case 'TransactionDate':
                                                  $transactionDate = $item['Value'];
                                                  break;
                                        case 'PhoneNumber':
                                                  $msisdn = $this->sanitizeAndFormatMobile($item['Value']);
                                                  break;
                                        default:
                                                  // Handle other cases as needed
                                                  break;
                              }
                    }

                    // Log the processed data for successful payment
                    Log::channel('mpesa')->info('Processed STK Callback data: ' . json_encode([
                              'MerchantRequestID' => $merchantRequestId,
                              'CheckoutRequestID' => $checkoutRequestId,
                              'amount' => $amount,
                              'TransactionDate' => $transactionDate,
                              'MpesaReceiptNumber' => $mpesaReceiptNumber,
                              'msisdn' => $msisdn
                    ]));

                    // Save payment details to the database using the MpesaCallback model
                    $payment = StkRequest::where('CheckoutRequestID', $checkoutRequestId)->first();
                    if ($payment) {
                              $payment->status = 'Paid';
                              $payment->TransactionDate = $transactionDate;
                              $payment->MpesaReceiptNumber = $mpesaReceiptNumber;
                              $payment->ResultDesc = $resultDesc;
                              $payment->save();

                              return response()->json([
                                        'status' => 'success',
                                        'message' => 'Payment processed successfully'
                              ], 200);
                    }

                    return response()->json([
                              'status' => 'error',
                              'message' => 'Payment not found'
                    ], 404);
          }

          /**
           * Helper method to sanitize and format phone numbers.
           */
          private function sanitizeAndFormatMobile($phoneNumber)
          {
                    // Add your logic here to sanitize and format the phone number
                    return preg_replace(
                              '/[^0-9]/',
                              '',
                              $phoneNumber
                    ); // Example to remove non-numeric characters
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
