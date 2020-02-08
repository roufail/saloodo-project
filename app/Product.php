<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = ['title','description','price','discount_amount','discount_type'];

    public static function boot() {
        parent::boot();
        // delete photos and cover in deleting
        static::deleting(function($product) {
            self::deletePhotos($product);
            self::deleteCover($product);
            return true;
        });

        // upload photos and attach bundle products in created event
        static::created(function($product) {
            self::upload_product_photos($product);
            self::attach_products($product);
            return true;
        });


        // static::updating(function($product) { // not working in our case
        //     self::upload_product_photos($product);
        //     return false;
        // });

    }

    public static function upload_product_photos($product)
    {
        // check if form has photos
        if(request()->hasFile('photos')) {
            // check if this update method to delete old photos
            if(request()->method() != 'POST')
                self::deletePhotos($product);

            $records = [];
            foreach (request()->file('photos') as $photo) {
               $file = $photo->store('public/products'); //  upload photos into storage folder
               $records[] = [
                'url'  => $file,
                'type' => 'photo',
               ];
            }
            $product->photos()->createMany($records); // insert photos records
        }

        // check if form has cover
        if(request()->file('cover')) {
            // check if this update method to delete old cover
            if(request()->method() != 'POST')
                self::deleteCover($product,true);
            //  upload cover into storage folder
            $cover = request()->cover->store('public/products');
            //create or update cover
            $product->cover()->updateOrCreate(['type'=>'cover','photoable_id'=>$product->id],[
                'url' => $cover,
                'type' => 'cover',
            ]);

        }

    }

    public static function deletePhotos($product) {
        // delete photos
        $photos = $product->photos()->pluck('url')->toArray();
        \Storage::delete($photos);
        $product->photos()->delete();
    }


    public static function deleteCover($product,$record = false) {
        // delete cover
        if($product->cover) // if contain cover delete it
            \Storage::delete($product->cover->url);
        if($record){
            $product->cover()->delete();
        }
    }

    public static function attach_products($product) {
        if(request()->bundle == 'true' || request()->bundle == 'True') {
            $childs = [];
            $product->setChilds($childs);
        }
    }


    public function cover() {
        return $this->morphOne(Photo::class,'photoable')->where("type","cover");
    }

    public function photos() {
        return $this->morphMany(Photo::class,'photoable')->where("type","photo");
    }

    public function setChilds($childs) {
        $childs = implode(',',$childs);
        \App\Bundle::updateOrCreate(['bundle_id' => $this->id],['products' => $childs]);
    }

    public function getChilds() {
        $ids = \App\Bundle::where('bundle_id',$this->id)->first('products');
        if($ids && $ids->products != '') {
            return \App\Product::wherein('id',explode(',',$ids->products))->get();
        }
        return null;
    }



}
