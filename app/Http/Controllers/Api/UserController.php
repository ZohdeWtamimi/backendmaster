<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(
            User::query()->orderBy('id', 'desc')->paginate(10)
        );
        // return "hello";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        // when i add two column i tried a lot to insert them but it wouldn't inserted coz its weren't in filable
        $data = $request->validated();
        // $newImageName = time() . '_' . $request->name . '.' . 
        // $request->image->extension();
        // $request->image->move(public_path('images'), $newImageName); 
        // if($request->hasFile('image')){
        // }
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        if($request->hasFile('image')){
            $exts = array('jpg','png','jpeg');
            if( !in_array($request->image->extension(), $exts)){
                return response()->json(['message'=> 'The image must be a file of type: jpg, png, jpeg.'],422);
            }
            $newImageName = time() . '_' . $data['name'] . '.' . 
            $request->image->extension();
            $request->image->move(public_path('images'), $newImageName); 
            $user->image()->create([
                'url' =>  $newImageName
            ]);
        }
        
        return response(new UserResource($user), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // this method expired coz laravel wouldn't accept formData with method PUT
        // altrenative next one

        // return response()->json(['name' => $request->name]);
        // $data = $request->validated();
        // if(isset($data['password'])){
        //     $data['password'] = bcrypt($data['password']);
        // }
        // $user->update($data);
        // if(isset($data['image'])){
        //     $newImageName = time() . '_' . $request->name . '.' . 
        //     $request->image->extension();
        //     $request->image->move(public_path('images'), $newImageName); 
        //     $user->image()->update([
        //         'url' =>  $newImageName
        //     ]);
        // }
        
        // return new UserResource($user);
    }
    /**
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // we type user as a param coz we assign it in the route at api.php
    public function myedit(UpdateUserRequest $request,User $user){
        $data = $request->validated();
        // if(isset($data['password'])){
        //     $data['password'] = bcrypt($data['password']);
        // }
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'role' => $data['role'],
        ]);
        if($request->hasFile('image')){
            $exts = array('jpg','png','jpeg');
            if( !in_array($request->image->extension(), $exts)){
                return response()->json(['message'=> 'The image must be a file of type: jpg, png, jpeg.'],422);
            }
            $newImageName = time() . '_' . $data['name'] . '.' . 
            $request->image->extension();
            $request->image->move(public_path('images'), $newImageName); 
            if($user->image == null){
                $user->image()->create([
                    'url' =>  $newImageName
                ]);
            }else{
                $user->image()->update([
                    'url' =>  $newImageName
                ]);
            }
        }
        // if(isset($data['image'])){
        //     $newImageName = time() . '_' . $data['name'] . '.' . 
        //     $request->image->extension();
        //     $request->image->move(public_path('images'), $newImageName); 
        //     if($user->image == null){
        //         $user->image()->create([
        //             'url' =>  $newImageName
        //         ]);
        //     }else{
        //         $user->image()->update([
        //             'url' =>  $newImageName
        //         ]);
        //     }
        // }
        
        return new UserResource($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->image()->delete();
        $user->delete();

        return response('', 204);
    }
}
