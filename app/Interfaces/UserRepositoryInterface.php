<?php
namespace App\Interfaces;
interface UserRepositoryInterface {
    public function register($request,$type);
    public function login($type);
    public function logout($type);
    public function user($type);
}
