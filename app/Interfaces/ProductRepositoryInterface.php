<?php
namespace App\Interfaces;
interface ProductRepositoryInterface {
    public function get($limit);
    public function create($product);
    public function show($product);
    public function update($request,$product);
    public function delete($product);
    public function addtocart($product);
    public function cart();
    public function order();
    public function get_order($orderid);
    public function change_cart_item_count($itemid,$request);
    public function my_orders();
    public function admin_orders();
    public function admin_get_order($orderid);
    public function admin_get_user_orders($userid);

}
