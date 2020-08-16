<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Validator;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\File; 

class ProductController extends Controller
{
    //public list 
    public function showAll(Request $request){
        $product = Product::all();
        if(count($product) == 0)   return ResponseController::sendError('Products not found.');
        else    return ResponseController::sendResponse($product, 'Products retrieved successfully.');
    }
    //loged user list
    public function showAllAdmin(Request $request){
        $product = Product::all();
        if(count($product) == 0)   return ResponseController::sendError('Products not found.');
        else    return ResponseController::sendResponse($product, 'Products retrieved successfully.');
    }
    /*
    Request with edit product 
    Input with product id, name, description, image, pricea, priceb, pricec
    */
    public function edit($id, Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'          => 'required|max:50',
            'description'   => 'max:255',
            'image'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pricea'        => 'required|numeric|between:0,10000000000.99',
            'priceb'        => 'numeric|between:0,10000000000.99',
            'pricec'        => 'numeric|between:0,10000000000.99',
        ]);
        if($validator->fails()){
            return ResponseController::sendError('Validation Error.', $validator->errors());
        }
        $product = Product::find($id);
        if(empty($product)){
            return ResponseController::sendError('Product not found');
        }
        else{
            $name = NULL;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time().mt_rand(10000,99999).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
            }
            if($name == NULL && $product->image != NULL) ProductController::removeImage($product->image);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->image = $name;
            $product->pricea = $request->pricea;
            $product->priceb = $request->priceb;
            $product->pricec = $request->pricec;
            $product->save();
            return ResponseController::sendResponse(new ProductResource($product), 'Product updated successfully.');
        }
    }
    /*
    Add new prouct
    input with name, description, image, pricea, priceb, pricec
    */
    public function add(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'          => 'required|max:50',
            'description'   => 'max:255',
            'image'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pricea'        => 'required|numeric|between:0,10000000000.99',
            'priceb'        => 'numeric|between:0,10000000000.99',
            'pricec'        => 'numeric|between:0,10000000000.99',
        ]);

        if($validator->fails()){
            return ResponseController::sendError('Validation Error.', $validator->errors());
        }else{
            $name = NULL;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time().mt_rand(10000,99999).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
            }
            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->image = $name;
            $product->pricea = $request->pricea;
            $product->priceb = $request->priceb;
            $product->pricec = $request->pricec;
            $product->created_at = date("Y-m-d H:i:s", time());
            $product->save();
        }
        return ResponseController::sendResponse(new ProductResource($product), 'Product created successfully.');
    }
    /*
    Delete product
    Input product id
    */
    public function delete($id, Request $request){
        $product = Product::find($id);
        if(is_null($product)){
            return ResponseController::sendError('Product not found');
        }
        else{
            ProductController::removeImage($product->image);
            $product->forceDelete();
            return ResponseController::sendResponse(null,'Product permanently deleted successfully.');
        }
    }
    //Function for removing product image
    private static function removeImage($name){
        File::delete('images/'.$name);
    }
}
