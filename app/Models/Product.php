<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductResource;
use App\Traits\Scopes;

class Product extends Model
{
    use Scopes;
    protected $table="products";

    protected $fillable = ['name','details','image'];

    public function scopeCreateProduct($query,$request){
        
        $products = Product::create($request->all());
        if($request->hasFile('image')){
            $path = '/public/product_image/'.$products->id;
            $name = Product::scopeUploadImage($request,$path);
            $products->image = $name;
            $products->save();
        }

        return User::GetMessage(new ProductResource($products),config('constants.messages.create_success'));
    }

    public static function productSearchSortQuery($query,$request){
        //search
        if (!empty($request->search)) {
            $query = $query->where('name', 'Like', '%'.$request->search.'%');
        }

        //sort
        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order ?? 'asc';
            
            if (in_array($sortBy, ['name', 'details'])) {
                $query = $query->orderBy($sortBy, $sortOrder);
            }
        }
        return $query;
    }

    public static function scopeChangeProductData($query,$request,$product){
        $product->update($request->all());
        if($request->hasFile('image')){
            $path = '/public/product_image/'.$product->id;
            Product::scopeFileExists($path,$product->image);
            $name = Product::scopeUploadImage($request,$path);
            $product->image = $name;
            $product->save();
        }
        return User::GetMessage(new ProductResource($product),config('constants.messages.update_success'));
    }

    public static function deleteProduct($product){
        $product->delete();

        return User::GetMessage(new ProductResource($product),config('constants.messages.delete_success'));
    }
}
