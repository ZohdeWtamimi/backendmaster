<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->image?->url,
            'category_id' => $this->category_id,
            'productName' => $this->productName,
            'productPrice' => $this->productPrice,
            'productDescription' => $this->productDescription,
            'productDiscount' => $this->productDiscount,
        ];
        // return [
        //     'id' => $this->id,
        //     'url' => $this->image?->url,
        //     'category_id' => $this->category_id,
        //     'productName' => $this->productName,
        //     'productPrice' => $this->productPrice,
        //     'productDescription' => $this->productDescription,
        //     'productDiscount' => $this->productDiscount,
        //     'related' =>  Category::findOrFail($this->category_id)->products,
        //     // 'related' =>  ProductResource::collection(Category::findOrFail($this->category_id)->products),
        //     // 'related' =>  ProductResource::collection(
        //     //     Product::query()->orderBy('id', 'desc')->where('category_id', $this->category_id)
        //     // ),
        //     'comments' => CommentResource::collection($this->comments),
        //     'categories' => Category::all()
        // ];

    }
}
