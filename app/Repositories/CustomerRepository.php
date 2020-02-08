<?php
namespace App\Repositories;
use App\Interfaces\UserRepositoryInterface;
// use App\Customer;
use Auth;
class AdminRepository implements UserRepositoryInterface {

    public function login($request){
        if(Auth::guard('customer')->attempt(['email' => $request->email,'password' => $request->password])){
            $user = Auth::guard('admin')->user();
            $success['token'] =  $user->createToken('admin')->accessToken;
            return response()->json(['user' => $success], 200);
        }
        else{
            return ['status' => false,'error' => 'error','message' => 'Check your email and password!','data' => []];
        }
    }



    public function logout() {
        if(Auth::guard('customer-api')->user()->token()->delete()) {
            return response()->json(['message' => 'User Logged out','status' => true], 200);
        }
        return $this->errorResponse();
    }

    public function user() {
        return Auth::guard('customer-api')->user();
    }

    public function errorResponse() {
        return ['status' => false,'error' => 'error','message' => 'Something went wrong!','data' => []];
    }

}
