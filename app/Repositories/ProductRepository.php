<?php
namespace App\Repositories;
use App\Interfaces\ProductRepositoryInterface;
use App\Product;
use App\Order;
use App\OrderItem;
use App\User;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\OrderResource;
use Auth;
class ProductRepository implements ProductRepositoryInterface {

    private $cart_total_amount = 0;
    private $cart_items_amount  = 0;
    private $user;

    public function __construct(){
        $this->user = Auth::guard('customer-api')->user();
    }

    public function get($limit) {
        return ProductResource::collection(Product::paginate($limit));
    }

    public function create($product) {
        if($product = Product::create($product->all())) {
            $data =  ['status' => 'success','message' => 'product created successfully' ,'data' => new ProductResource($product)];
            return response()->json($data, 200);
        } else {
            return response()->json($this->errorResponse(),500);
        }
    }


    public function show($product) {

        if($product) {
            $data =  ['status' => 'success','message' => 'product data' ,'data' => new ProductResource($product)];
            return response()->json($data, 200);
        } else {
            return response()->json($this->errorResponse(),500);
        }
    }

    public function update($request,$product){
        if($product->update($request->all())) {
            Product::upload_product_photos($product);
            Product::attach_products($product);
            $data =  ['status' => 'success','message' => 'product updated successfully' ,'data' => new ProductResource($product)];
            return response()->json($data, 200);
        } else {
            return response()->json($this->errorResponse(),500);
        }

    }

    public function delete($product){
        if($product->delete()) {
            $data =  ['status' => 'success','message' => 'product deleted successfully' ,'data' => []];
            return response()->json($data, 200);
        } else {
            return response()->json($this->errorResponse(),500);
        }
    }


    public function admin_orders() {
        $orders = Order::paginate(10);
        return ['status' => true,'message' => 'Orders data','data' => OrderResource::collection($orders)];
    }

    public function admin_get_order($orderid) {
        $order = Order::find($orderid);
        if($order) {
            return ['status' => true,'message' => 'Order contents','data' => new OrderResource($order)];
        }
        return ['status' => false,'message' => 'this order doesn\'t exists','order' => []];
    }

    public function admin_get_user_orders($userid) {
        $user = User::find($userid);
        if($user)
        {
            $orders = Order::where('user_id',$userid)->paginate(10);
            return ['status' => true,'message' => 'Orders data','data' => OrderResource::collection($orders)];
        }
        return $this->errorResponse();
    }



    // user section
    public function addtocart($product) {
        $this->user->cart()->updateOrCreate([]);

        $this->user->cart->items()->updateOrCreate(['cart_item_id' => $product->id],[
            'cart_item_id' => $product->id,
            // 'count'      => \DB::raw('count + 1') // did't work
        ])->increment('count');
        return ['status' => true,'message' => 'product added to cart','data' => new ProductResource($product)];
    }

    public function cart() {

        if($this->user->cart) {

            $this->user->cart->items->map(function($item){
                $this->cart_total_amount += calculateDiscount($item->product->price,$item->product->discount_type,$item->product->discount_amount) * $item->count;
                $this->cart_items_amount += $item->count;
            });

            return [
                'status' => true,
                'message' => 'cart contents',
                'products' => CartItemResource::collection($this->user->cart->items),
                "cart_total"  => $this->cart_total_amount,
                'items_count' => $this->cart_items_amount,
                'cart_id'     => $this->user->cart->id
            ];
        }
        return ['status' => false,'error' => 'error','message' => 'Sorry you haven\'t any products in cart! add some frist','data' => []];
    }

    public function order() {
        if($this->user->cart) {
            $order = $this->user->cart->placeOrder();

            return ['status' => true,'message' => 'Order data','data' => new OrderResource($order)];
        }
        return ['status' => false,'error' => 'error','message' => 'Sorry you haven\'t any products in cart! add some frist','data' => []];
    }


    public function get_order($orderid) {
        $order = $this->user->orders()->find($orderid);
        if($order) {
            return ['status' => true,'message' => 'Order contents','data' => new OrderResource($order)];
        }
        return ['status' => false,'message' => 'this order doesn\'t exists','order' => []];
    }


    public function change_cart_item_count($itemid,$newcount = 0) {
        $item = OrderItem::find($itemid); // get item
        if($item) { // if item
            $order = $this->user->orders()->find($item->order_id);
            if($order) { // if current order belongs to current user
               if($newcount > 0) { //  if count > 0 update the value
                    $item->update([
                        'count' => $newcount
                    ]);
                    return ['status' => true,'message' => 'item updated','data' => ['count' => $newcount]];
               } else {  //  if count <= 0 delete the item
                    $item->delete();
                    return ['status' => true,'message' => 'item deleted','data' => ['count' => 0]];
               }
            } else {
                return ['status' => false,'error' => 'error','message' => 'you have no permissions to access this order','data' => []];
            }
        }else {
            return ['status' => false,'error' => 'error','message' => 'item not exist!','data' => []];
        }
        return $this->errorResponse();
    }

    public function my_orders() {
        $orders = $this->user->orders()->paginate(10);
        return ['status' => true,'message' => 'Orders data','data' => OrderResource::collection($orders)];
    }




    public function errorResponse() {
        return ['status' => false,'error' => 'error','message' => 'Something went wrong!','data' => []];
    }

}
