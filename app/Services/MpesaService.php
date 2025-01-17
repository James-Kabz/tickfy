<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MpesaService
{
          protected $consumerKey;
          protected $consumerSecret;
          protected $baseUrl;

          public function __construct()
          {
                    $this->consumerKey = env('MPESA_CONSUMER_KEY');
                    $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
                    $this->baseUrl = env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke');
          }

          public function getAccessToken()
          {
                    $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                              ->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

                    return $response->json()['access_token'];
          }

          public function initiatePayment($phoneNumber, $amount, $accountReference, $transactionDesc)
          {
                    $accessToken = $this->getAccessToken();

                    $response = Http::withToken($accessToken)->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', [
                              'BusinessShortCode' => env('MPESA_SHORTCODE'),
                              'Password' => base64_encode(env('MPESA_SHORTCODE') . env('MPESA_PASSKEY') . now()->format('YmdHis')),
                              'Timestamp' => now()->format('YmdHis'),
                              'TransactionType' => 'CustomerPayBillOnline',
                              'Amount' => $amount,
                              'PartyA' => $phoneNumber,
                              'PartyB' => env('MPESA_SHORTCODE'),
                              'PhoneNumber' => $phoneNumber,
                              'CallBackURL' => route('mpesa.callback'),
                              'AccountReference' => $accountReference,
                              'TransactionDesc' => $transactionDesc,
                    ]);

                    return $response->json();
          }

          public function callback(Request $request)
          {
                    $data = $request->all();

                    if ($data['Body']['stkCallback']['ResultCode'] === 0) {
                              // Payment successful
                              $transaction = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
                              $phone = collect($transaction)->where('Name', 'PhoneNumber')->first()['Value'];
                              $amount = collect($transaction)->where('Name', 'Amount')->first()['Value'];

                              // Save transaction and send email
                              // Notify user of success
                    } else {
                              // Handle payment failure
                    }

                    return response()->json(['status' => 'success']);
          }

}
