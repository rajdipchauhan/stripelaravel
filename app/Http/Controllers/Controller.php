<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Cartalyst\Stripe\Stripe;
use Cartalyst\Stripe\Exception;
use Illuminate\Http\Request;
use View;
use App\Transactions;
use App\PaymentCards;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function GetCustomers()
    {
    	$key = getenv('STRIPE_API_KEY');
            $stripe = Stripe::make($key);
            $response_data = array();
            $customers = $stripe->customers()->all();

            foreach ($customers['data'] as $customer) {
                array_push($response_data,$customer);
            }    
             return response()->json($response_data);
    }

    public function GetCustomersById()
    {
    	$key = getenv('STRIPE_API_KEY');
		$stripe = Stripe::make($key);

		$customers = $stripe->customers()->all();

		foreach ($customers['data'] as $customer) {
			echo "<pre>";
		    print_r($customer);
		}    
    }
    
    public function MakePayment() {
         $transcount = DB::table('transactions')->count();
         $data['order_id'] = $transcount +1;
         return view('makepayment',compact('data'));
    }
    
    public function SaveDirectPayment(Request $request) {
        $key = getenv('STRIPE_API_KEY'); 
        $stripe = Stripe::make($key);
        $cus_id = 'cus_9cwdWWO0WvTYpx';
        $cardExec = 'No';
        $ChargeExec = 'No';
        
        try {
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request['card_number'],
                    'exp_month' => $request['exp_month'],
                    'cvc' => $request['cvv'],
                    'exp_year' => $request['exp_year'],
                ],
            ]);
        }
        catch (\Cartalyst\Stripe\Exception\InvalidRequest $e) {
            $cardExec = 'Yes';
            $message = $e->getMessage();
        }
        catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            $cardExec = 'Yes';
            $message = $e->getMessage();
        }    
        catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            $cardExec = 'Yes';
            $message =  $e->getMessage();
        }
        catch (\Cartalyst\Stripe\Exception $e) {
            $cardExec = 'Yes';
            $message = 'Something else happened, completely unrelated to Stripe';
        }
        if($cardExec=='Yes'){
            return redirect('makepayment')->with('status',$message)->withInput($request->input());
        }
        

        if($request['is_check']=='Yes'){

            try {
                $card = $stripe->cards()->create($cus_id, [
                    'number'    => $request['card_number'],
                    'exp_month' => $request['exp_month'],
                    'cvc'       => $request['cvv'],
                    'exp_year'  => $request['exp_year'],
                ]);

                $paymentcard = new PaymentCards();
                $paymentcard->user_id = \Auth::User()->id;
                $paymentcard->cardId = $card['id'];
                $paymentcard->brand = $card['brand'];
                $paymentcard->fingerPrint = $card['fingerprint'];
                $paymentcard->last4 = $card['last4'];
                $paymentcard->created_at = date('Y-m-d h:i:s');
                $paymentcard->updated_at = date('Y-m-d h:i:s');
                $paymentcard->save();
            }
            catch (\Cartalyst\Stripe\Exception\InvalidRequest $e) {
                $cardExec = 'Yes';
                $message = $e->getMessage();
            }
            catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                $cardExec = 'Yes';
                $message = $e->getMessage();
            }    
            catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $cardExec = 'Yes';
                $message =  $e->getMessage();
            }
            catch (\Cartalyst\Stripe\Exception $e) {
                $cardExec = 'Yes';
                $message = 'Something else happened, completely unrelated to Stripe';
            }
            if($cardExec=='Yes'){
                return redirect('makepayment')->with('status',$message)->withInput($request->input());
            }
        }

        $charge = $stripe->charges()->create([
            'source' => $token['id'],
            'currency' => 'USD',
            "description" => $request['order_desc'],
            'amount'   => $request['amount'],
        ]);
        
        if($charge['status'] == 'succeeded'){
            $paymentStatus = 'Completed';
            $transactions = new Transactions();
            $transactions->user_id = \Auth::User()->id;
            $transactions->orderId = $request['order_id'];
            $transactions->order_desc = $request['order_desc'];
            $transactions->paymentCardId = $charge['source']['id'];
            $transactions->fullPaymentResponse = json_encode($charge);
            $transactions->amount = $request['amount'];
            $transactions->paymentStatus = $paymentStatus;
            $transactions->created_at = date('Y-m-d h:i:s');
            $transactions->updated_at = date('Y-m-d h:i:s');
            $transactions->save();
            return redirect('home')->with('status','Payment Done successfully!');
        }else{
            $paymentStatus = 'Failed';
            return redirect('home')->with('status','Payment Declined!');
        }
    }
    
    public function TransactionsLog() {
         $transdata = DB::table('transactions')->get();
         return view('transactions',compact('transdata'));
    }

    public function StoredCardPayment() {
         $transcount = DB::table('transactions')->count();
         $cardsdata = DB::table('paymentCards')->get();
         $data['order_id'] = $transcount +1;
         $data['cardsdata'] =$cardsdata;
         return view('storedcardpayment',compact('data'));
    }

    public function SaveCardPayment(Request $request) {
        $key = getenv('STRIPE_API_KEY'); 
        $stripe = Stripe::make($key);
        $cus_id = 'cus_9cwdWWO0WvTYpx';
        $cardExec = 'No';
        $ChargeExec = 'No';
        
        try {
            $charge = $stripe->charges()->create([
                'cards' => $request['optradio'],
                'currency' => 'USD',
                "description" => $request['order_desc'],
                'amount'   => $request['amount'],
                'customer' => $cus_id,
            ]);
        }
        
        catch (\Cartalyst\Stripe\Exception\InvalidRequest $e) {
            $cardExec = 'Yes';
            $message = $e->getMessage();
        }
        catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            $cardExec = 'Yes';
            $message = $e->getMessage();
        }    
        catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            $cardExec = 'Yes';
            $message =  $e->getMessage();
        }
        catch (\Cartalyst\Stripe\Exception $e) {
            $cardExec = 'Yes';
            $message = 'Something else happened, completely unrelated to Stripe';
        }
        if($cardExec=='Yes'){
            return redirect('storedcardpayment')->with('status',$message)->withInput($request->input());
        }
        
        if($charge['status'] == 'succeeded'){
            $paymentStatus = 'Completed';
            $transactions = new Transactions();
            $transactions->user_id = \Auth::User()->id;
            $transactions->orderId = $request['order_id'];
            $transactions->order_desc = $request['order_desc'];
            $transactions->paymentCardId = $charge['source']['id'];
            $transactions->fullPaymentResponse = json_encode($charge);
            $transactions->amount = $request['amount'];
            $transactions->paymentStatus = $paymentStatus;
            $transactions->created_at = date('Y-m-d h:i:s');
            $transactions->updated_at = date('Y-m-d h:i:s');
            $transactions->save();
            return redirect('home')->with('status','Payment Done successfully using stored cards!');
        }else{
            $paymentStatus = 'Failed';
            return redirect('home')->with('status','Payment Declined!');
        }
    }
    
    public function CardList() {
         $cardsdata = DB::table('paymentCards')->get();
         return view('cardlist',compact('cardsdata'));
    }

    
}
