<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProductRepositoryInterface;
use App\Product;

class ProductsController extends Controller
{

    protected $productRepo;
    public function __construct(ProductRepositoryInterface $productRepo){
        $this->productRepo = $productRepo;
    }

    public function products() {
        return $this->productRepo->get(10);
    }

    public function show(Product $product)
    {
        return $this->productRepo->show($product);
    }

    public function addtocart(Product $product)
    {
        return $this->productRepo->addtocart($product);
    }

    public function cart() {
        return $this->productRepo->cart();
    }

    public function order() {
        return $this->productRepo->order();
    }

    public function change_cart_item_count($itemid,Request $request) {
        $request->validate([
            'count' => 'required|integer|min:0'
        ]);
        return $this->productRepo->change_cart_item_count($itemid,$request->count);
    }

    public function delete_cart_item($itemid) {
        return $this->productRepo->change_cart_item_count($itemid,0);
    }

    public function get_order($orderid) {
        return $this->productRepo->get_order($orderid);
    }


    public function my_orders() {
        return $this->productRepo->my_orders();
    }




}
