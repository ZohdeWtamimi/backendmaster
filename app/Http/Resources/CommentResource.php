<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'user_url' => $this->user->image?->url,
        //     'user_name' => $this->user->name,
        //     'category_name' => $this->product->category->CategoryName,
        //     // 'product' => $this->product->category->id,
        //     'productName' => $this->product->productName,
        //     'productDescription' => $this->product->productDescription,
        //     'productDiscount' => $this->product->productDiscount,
        //     'productPrice' => $this->product->productPrice,
        //     'user_id' => $this->user_id,
        //     'product_id' => $this->product_id,
        //     'body' => $this->body,
        //     'created_at' =>$this->created_at->format('Y-m-d H:i:s')
        // ];
        return [
            'id' => $this->id,
            'user_url' => $this->user->image?->url,
            'user_name' => $this->user->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'body' => $this->body,
        ];
    }
}
