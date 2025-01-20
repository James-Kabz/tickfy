<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Event;
use App\Models\StkRequest;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
public function show($eventId)
{
    $event = Event::findOrFail($eventId);

    $ticketDetails = session('ticketDetails', null);

    if (!$ticketDetails) {
        return redirect()->route('events.show', $eventId)->with('error', 'No ticket details found.');
    }

    return view('payment', [
        'event' => $event,
        'ticketDetails' => $ticketDetails,
    ]);
}


          public function token()
          {
                    $consumerKey = 'TFDmQGinhsNcND76vx2D4SHBvx1Qk06nbsozonkm0rqv56uR';
                    $consumerSecret = 'a5z2QeYN3Whl5585tV8bcnVsIyHlQ6tNldQWUFg6VfHnrPXPKUzFaby97Q6QtupO';
                    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

                    $response = Http::withBasicAuth($consumerKey, $consumerSecret)->get($url);


                    if ($response->failed()) {
                              throw new Exception('Failed to obtain access token: ' . $response->body());
                    }

                    return $response->json()['access_token'];
          }

          public function paymentStatus()
          {
                    return view('ticket.status', ['message' => session('message')]);
          }


          public function initiateStkPush(Request $request)
          {
                    try {
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
                                        'CallBackURL' => 'https://475d-197-232-1-50.ngrok-free.app/payments/stkcallback',
                                        'AccountReference' => $AccountReference,
                                        'TransactionDesc' => $TransactionDesc,
                              ]);

                              if ($response->failed()) {
                                        return response()->json([
                                                  'error' => 'Failed to initiate STK Push: ' . $response->body(),
                                        ], 500);
                              }

                              $res = $response->json();
                              if (isset($res['ResponseCode']) && $res['ResponseCode'] == 0) {
                                        // Save request to database
                                        StkRequest::create([
                                                  'phone' => $PhoneNumber,
                                                  'amount' => $Amount,
                                                  'reference' => $AccountReference,
                                                  'description' => $TransactionDesc,
                                                  'MerchantRequestID' => $res['MerchantRequestID'],
                                                  'CheckoutRequestID' => $res['CheckoutRequestID'],
                                                  'status' => 'Requested',
                                        ]);

                                        // Redirect with success message
                                        return redirect()->route('ticket.status')->with('message', $res['CustomerMessage']);
                              }

                              return response()->json(['error' => 'STK Push failed: ' . $res['ResponseDescription']], 500);
                    } catch (Throwable $e) {
                              return response()->json(['error' => $e->getMessage()], 500);
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

                    if (is_null($checkoutRequestId)) {
                              Log::channel('mpesa')->error('CheckoutRequestID is missing in the callback.');
                              return response()->json(['status' => 'error', 'message' => 'Invalid callback data'], 400);
                    }

                    // Handle failed payment callback
                    if ($resultCode !== 0) {
                              Log::channel('mpesa')->error('STK Callback failed with ResultCode: ' . $resultCode . ' - ' . $resultDesc);

                              // Update payment status to failed in the database
                              $payment = StkRequest::where('CheckoutRequestID', $checkoutRequestId)->first();
                              if ($payment) {
                                        $payment->status = 'Failed';
                                        $payment->ResultDesc = $resultDesc;
                                        $payment->save();
                              }

                              return response()->json(['status' => 'error', 'message' => $resultDesc], 500);
                    }

                    // Handle successful payment callback
                    $callbackMetadata = $callbackData['CallbackMetadata']['Item'] ?? [];
                    if (empty($callbackMetadata)) {
                              Log::channel('mpesa')->error('CallbackMetadata is missing or empty.');
                              return response()->json(['status' => 'error', 'message' => 'Invalid callback metadata'], 400);
                    }

                    // Initialize metadata fields
                    $amount = null;
                    $transactionDate = null;
                    $mpesaReceiptNumber = null;

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
                              }
                    }

                    // Save payment details to the database
                    $payment = StkRequest::where('CheckoutRequestID', $checkoutRequestId)->first();
                    if (!$payment) {
                              Log::channel('mpesa')->error('Payment record not found for CheckoutRequestID: ' . $checkoutRequestId);
                              return response()->json(['status' => 'error', 'message' => 'Payment record not found'], 404);
                    }

                    // Update payment record
                    $payment->status = 'Paid';
                    $payment->TransactionDate = $transactionDate;
                    $payment->MpesaReceiptNumber = $mpesaReceiptNumber;
                    $payment->ResultDesc = $resultDesc;
                    $payment->save();

                    // Redirect to ticket confirmation route
                    return response()->json([
                              'status' => 'success',
                              'message' => 'Payment processed successfully.',
                              'redirect_url' => route('ticket.confirmation', ['ticket_id' => $payment->reference]), // Assuming 'reference' is ticket ID
                    ], 200);
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

}
