<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PostController extends Controller
{
    protected $postModel;

    public function __construct(Post $postModel)
    {
      $this->postModel = $postModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = auth()->user();
      $posts = $this->postModel;

      if( $user->hasRole(['admin', 'editor']) ) {
        return response()->json(
          new PostCollection($posts->paginate())
          ,200);
      } 

      return response()->json(
        new PostCollection(
          $posts->where('user_id', $user->id)
        ->paginate())
      ,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
      $post = $this->postModel->create([
        'title' => $request["title"],
        'content' => $request["content"],
        'user_id' => auth()->user()->id
      ]);

      return response()->json(
        new PostResource($post)
        ,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $user = auth()->user();
      $post = $this->postModel;

      if($user->hasRole(['admin', 'editor'])) {
        return response()->json(
          new PostResource($post->find($id))
          ,200);
      }
      
      
      $post = $post->where([
        ['id', '=' ,$id],
        ['user_id', '=' , $user->id]
      ])
      ->first();

      if(!$post) 
        throw new UnauthorizedException(403);
        
      return response()->json(
        new PostResource($post),
        200
      );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
      $user = auth()->user();
      
      if($user->hasRole(['admin', 'editor'])) {
        $post->update([
          'title' => $request["title"],
          'content' => $request["content"],
        ]);

        return response()->json(
          new PostResource($post)
        , 200);
      }

      $post = $post->where('user_id', $user->id)->first();
      
      if (is_null($post)) 
        throw new UnauthorizedException(403);
      
      $post->update([
        'title' => $request['title'],
        'content' => $request['content'],
      ]);

      return response()->json(
        new PostResource($post),
        200
      );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
      $user = auth()->user();
      $output = ["message" => 'Post deleted'];

      if($user->hasRole(['admin', 'editor'])) {
        $post->delete();
        return response()->json(
          $output,
        200);
      }

      $post = $post->where('user_id', $user->id)->first();
      
      if (is_null($post)) 
        throw new UnauthorizedException(403);

      $post->delete();

      return response()->json(
        $output,
        200
      );
    }
}
