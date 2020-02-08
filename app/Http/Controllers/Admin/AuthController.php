<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{

    private $userRepo;
    public function __construct(UserRepositoryInterface $userRepo) {
        $this->userRepo = $userRepo;
    }
    public function login(Request $request) {
        return $this->userRepo->login('admin');
    }
    public function logout() {
        return $this->userRepo->logout('admin');
    }
    public function user() {
        return $this->userRepo->user('admin');
    }
}
