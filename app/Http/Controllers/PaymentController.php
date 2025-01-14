<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Event;
use App\Models\Ticket;

class PaymentController extends Controller
{
          // public function show(Request $request, Event $event)
          // {
          //           $ticketDetails = $request->input('ticket_types', []);
          //           // $tickets = $request->
          //           $grandTotal = $request->input('grand_total', 0);

          //           return view('payment', compact('event', 'ticketDetails', 'grandTotal'));
          // }


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

          public function initiatePayment(Request $request, MpesaService $mpesaService)
          {
                    $request->validate([
                              'phone' => 'required|regex:/^254[0-9]{9}$/',
                              'amount' => 'required|numeric|min:1',
                    ]);

                    $phone = $request->input('phone');
                    $amount = $request->input('amount');

                    $paymentResponse = $mpesaService->initiatePayment(
                              $phone,
                              $amount,
                              'EventTicket123',
                              'Payment for Event Ticket'
                    );

                    if ($paymentResponse['ResponseCode'] == '0') {
                              // Send email to user
                              Mail::to($request->user()->email)->send(new PaymentConfirmationMail($amount));

                              return back()->with('status', 'Payment initiated successfully. Check your phone to complete the process.');
                    }

                    return back()->withErrors(['error' => 'Failed to initiate payment. Please try again.']);
          }
}
