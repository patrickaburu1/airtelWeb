<?php

namespace App\Http\Controllers;

use App\Airtime;
use App\Http\Middleware\TrimStrings;
use App\Receive;
use App\Sent;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{

    //send money
  public function send(Request $request,$person_id){

      $today = Carbon::today();
      $current= $today->toDateString();

      $amount=$request->input('amount');
      $phone=$request->input('phone');
      //$t_type=$request->input('t_type');

      $checkBalance=DB::table('accounts')
          ->where('person_id','=',$person_id)
          ->first();

      $receiver=DB::table('people')
          ->where('phoneNumber','=',$phone)
          ->first();

      $balance=$checkBalance->balance;

      if(empty($receiver) )
            {
                $result = array();
                $result['id'] = 0;
                return Response::json($result);

      }
      else{
            if(($balance >= $amount)){

          //do transactions
          $transaction=new Transaction();
          $transaction->type=1;
          $transaction->amount=$amount;
          $transaction->date=$current;
          $transaction->code="h783";
          $transaction->person_id=$person_id;
          $transaction->save();

          $send=new Receive();
          $send->t_id=$transaction->id;
          $send->person_id=$receiver->id;
          $send->save();

                DB::table('accounts')->where('person_id','=',$person_id)->decrement('balance', $amount);

                DB::table('accounts')->where('person_id','=',$receiver->id)->increment('balance', $amount);

          $send=new Sent();
          $send->t_id=$transaction->id;
          $send->person_id=$person_id;
          $send->save();

          return Response::json($send);
      }
      else{
          $result = array();
          $result['id'] = 1;
          return Response::json($result);

      }
      }
  }

  // buy airtime my phone
    public function buyAirtime(Request $request,$person_id){
        $today = Carbon::today();
        $current= $today->toDateString();

        $amount=$request->input('amount');

        $user=DB::table('people')
            ->where('id','=',$person_id)
            ->first();

        $checkBalance=DB::table('accounts')
            ->where('person_id','=',$person_id)
            ->first();

        $balance=$checkBalance->balance;

        if(($balance >= $amount)) {

            //do transactions
            $transaction = new Transaction();
            $transaction->type = 2;
            $transaction->amount = $amount;
            $transaction->date = $current;
            $transaction->code = "A783";
            $transaction->person_id = $person_id;
            $transaction->save();

//            $airtime=new Airtime();
//            $airtime->t_id=$transaction->id;
//            $airtime->boughtTo=$user->id;
//            $airtime->save();

            DB::table('accounts')->where('person_id','=',$person_id)->decrement('balance', $amount);

            return Response::json($transaction);
        }
        else{
            $result = array();
            $result['id'] = 0;
            return Response::json($result);


        }

    }

    public function buyAirtimeOther(Request $request,$person_id){
        $today = Carbon::today();
        $current= $today->toDateString();

        $amount=$request->input('amount');
        $phone=$request->input('phone');
        //$t_type=$request->input('t_type');

        $checkBalance=DB::table('accounts')
            ->where('person_id','=',$person_id)
            ->first();

        $balance=$checkBalance->balance;

        if(($balance >= $amount)) {

            //do transactions
            $transaction = new Transaction();
            $transaction->type = 1;
            $transaction->amount = $amount;
            $transaction->date = $current;
            $transaction->code = "AO783".$phone;
            $transaction->person_id = $person_id;
            $transaction->save();

//            $airtime=new Airtime();
//            $airtime->t_id=$transaction->id;
//            $airtime->boughtTo=$phone;
//            $airtime->save();

            DB::table('accounts')->where('person_id','=',$person_id)->decrement('balance', $amount);

            return Response::json($transaction);
        }
        else{
            $result = array();
            $result['id'] = 0;
            return Response::json($result);


        }

    }
}
