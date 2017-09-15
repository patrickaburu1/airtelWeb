<?php

namespace App\Http\Controllers;

use App\Account;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    public function login(Request $request){
        $phone=$request->input('phone');
        $password=$request->input('password');
        $check_user=DB::table('people')
            ->where('phoneNumber','=',$phone)
            ->first();
        //  return Response::json($check_user);
        if(empty($check_user)){
            $result = array();
            $result['role'] = 0;
            return Response::json($result);
        }else{

            if (Hash::check($password, $check_user->password)) {
                // $person_id= $check_user->id;
                return Response::json($check_user);
            }
            else{
                $result = array();
                $result['role'] = 0;
                return Response::json($result);

            }

        }
    }

    //register new user
    public function register(Request $request){
        $phone=$request->input('phone');
        $password=$request->input('password');
        $fname=$request->input('fname');
        $lname=$request->input('lname');

        $password1=bcrypt($password);

        $addUser=new Person();
        $addUser->firstName=$fname;
        $addUser->lastName=$lname;
        $addUser->phoneNumber=$phone;
        $addUser->password=$password1;
        $addUser->role=1;
        $addUser->pin=1234;
        $addUser->Save();

        $account=new Account();
        $account->person_id=$addUser->id;
        $account->balance=10000;
        $account->save();

        return Response::json($addUser);

    }
}
