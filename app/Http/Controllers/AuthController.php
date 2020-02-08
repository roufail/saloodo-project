<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{

    private $userRepo;
    public function __construct(UserRepositoryInterface $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|min:5|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);
        return $this->userRepo->register($request,'customer');
    }
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
        return $this->userRepo->login('customer');
    }
    public function logout() {
        return $this->userRepo->logout('customer');
    }
    public function user() {
        return $this->userRepo->user('customer');
    }
}
