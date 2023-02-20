<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection(
            Product::query()->orderBy('id', 'desc')->paginate(10)
        );
        // return ProductResource::collection(
        //     Product::query()->orderBy('id', 'desc')->where('productPrice', '>', 500)->paginate(10)
        // );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {  
        // when you apply conditon
        // if($request['minPrice'] && $request['maxPrice'] && $request['condition']){
        //     return ProductResource::collection(
        //         Product::query()->orderBy('id', 'desc')->where('condition', 'used')->where('productPrice', '>', $request['minPrice'])->paginate(10)
        //     ); 
        // }
        // return response()->json(['minPrice'=> $request['minPrice'], 'maxPrice'=> $request['maxPrice']],201);
        // if($request['minPrice']){
        // }
        if($request['minPrice'] && $request['maxPrice']){
            return ProductResource::collection(
                Product::query()->orderBy('id', 'desc')->whereBetween('productPrice', [$request['minPrice'], $request['maxPrice']])->paginate(10)
            );
        }
        if($request['minPrice']){
            $request['minPrice'] = (int) $request['minPrice'];
            return ProductResource::collection(
                Product::query()->orderBy('id', 'desc')->where('productPrice', '>', $request['minPrice'])->paginate(10)
            );
        }
        if($request['maxPrice']){
            $request['maxPrice'] = (int) $request['maxPrice'];
            return ProductResource::collection(
            Product::query()->orderBy('id', 'desc')->where('productPrice', '<', $request['maxPrice'])->paginate(10)
        );
        }
        return ProductResource::collection(
            Product::query()->orderBy('id', 'desc')->paginate(10)
        );
        //  return response()->json(['request' => $request['minPrice']]) ;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $request['category_id'] = (int) $request['category_id']; 
        $data = $request->validated();
        $product = Product::create($data);
        if($request->hasFile('image')){
            $exts = array('jpg','png','jpeg');
            if( !in_array($request->image->extension(), $exts)){
                return response()->json(['message'=> 'The image must be a file of type: jpg, png, jpeg.'],422);
            }
            $newImageName = time() . '_' . $data['productName'] . '.' . 
            $request->image->extension();
            $request->image->move(public_path('images'), $newImageName); 
            $product->image()->create([
                'url' =>  $newImageName
            ]);
        }

        return response(new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // it's costumize resource coz I want key (related) and key related has product, 
        // so it's not make sense to use resource (ProductResource) inside ProductResource
        return new SingleProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // we type product as a param coz we assign it in the route at api.php
    public function myedit(UpdateProductRequest $request,Product $product){
        $data = $request->validated();
        $product->update([
            'category_id' => (int) $data['category_id'],
            'productName' => $data['productName'],
            'productPrice' => $data['productPrice'],
            'productDescription' => $data['productDescription'],
            'productDiscount' => $data['productDiscount'],
        ]);
        if($request->hasFile('image')){
            $exts = array('jpg','png','jpeg');
            if( !in_array($request->image->extension(), $exts)){
                return response()->json(['message'=> 'The image must be a file of type: jpg, png, jpeg.'],422);
            }
            $newImageName = time() . '_' . $data['productName'] . '.' . 
            $request->image->extension();
            $request->image->move(public_path('images'), $newImageName); 
            if($product->image == null){
                $product->image()->create([
                    'url' =>  $newImageName
                ]);
            }else{
                $product->image()->update([
                    'url' =>  $newImageName
                ]);
            }
        }
        
        
        return new ProductResource($product);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->image()->delete();
        $product->delete();

        return response('', 204);
    }
}
