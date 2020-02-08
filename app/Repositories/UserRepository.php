<?php
namespace App\Repositories;
use App\Interfaces\UserRepositoryInterface;
use Auth;
use App\User;
class UserRepository implements UserRepositoryInterface {


    public function register($reuest,$type) {
        if($User = User::create($reuest->all())){
            return response()->json(['message' => 'account created Successfully','status' => true], 200);
        }
        return $this->errorResponse();
    }
    public function login($type){
        if(Auth::guard($type)->attempt(['email' => request('email'),'password' => request('password')])){
            $user = Auth::guard($type)->user();
            $success['token'] =  $user->createToken($type)->accessToken;
            return response()->json(['user' => $success], 200);
        }
        else{
            return ['status' => false,'error' => 'error','message' => 'Check your email and password!','data' => []];
        }
    }



    public function logout($type) {
        if(Auth::guard($type.'-api')->user()->token()->delete()) {
            return response()->json(['message' => 'User Logged out','status' => true], 200);
        }
        return $this->errorResponse();
    }

    public function user($type) {
        return Auth::guard($type.'-api')->user();
    }

    public function errorResponse() {
        return ['status' => false,'error' => 'error','message' => 'Something went wrong!','data' => []];
    }

}
