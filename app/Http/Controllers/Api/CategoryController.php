<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(
            Category::query()->orderBy('id', 'desc')->paginate(10)
        );
        // $categories = CategoryResource::collection(
        //     Category::query()->orderBy('id', 'desc')->paginate(10)
        // );
        // $hello = ["hi"=>'bey'];
        // return response(['categories'=> $categories, 'hello' => $hello]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        if($request->hasFile('image')){
            $exts = array('jpg','png','jpeg');
            if( !in_array($request->image->extension(), $exts)){
                return response()->json(['message'=> 'The image must be a file of type: jpg, png, jpeg.'],422);
            }
            $newImageName = time() . '_' . $data['CategoryName'] . '.' . 
            $request->image->extension();
            $request->image->move(public_path('images'), $newImageName); 
            $category->image()->create([
                'url' =>  $newImageName
            ]);
        }

        return response(new CategoryResource($category), 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);

        return new CategoryResource($category);
    }

    /**
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    // we type category as a param coz we assign it in the route at api.php
    public function myedit(UpdateCategoryRequest $request,Category $category){
        $data = $request->validated();
        // if(isset($data['password'])){
        //     $data['password'] = bcrypt($data['password']);
        // }
        $category->update([
            'CategoryName' => $data['CategoryName'],
        ]);
        if($request->hasFile('image')){
            $exts = array('jpg','png','jpeg');
            if( !in_array($request->image->extension(), $exts)){
                return response()->json(['message'=> 'The image must be a file of type: jpg, png, jpeg.'],422);
            }
            $newImageName = time() . '_' . $data['CategoryName'] . '.' . 
            $request->image->extension();
            $request->image->move(public_path('images'), $newImageName); 
            if($category->image == null){
                $category->image()->create([
                    'url' =>  $newImageName
                ]);
            }else{
                $category->image()->update([
                    'url' =>  $newImageName
                ]);
            }
        }
        
        
        return new CategoryResource($category);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->image()->delete();
        $category->products()->delete();
        $category->delete();

        return response('', 204);
    }
}
