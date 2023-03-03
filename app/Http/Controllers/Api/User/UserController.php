<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  protected $userModel;

  public function __construct(User $userModel)
  {
    $this->userModel = $userModel;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = $this->userModel
      ->with('roles')
      ->paginate();

    return response()->json(
      new UserCollection($users),
      200
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserRequest $request)
  {
    $user = $this->userModel
      ->create([
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
      ]);

    $user->assignRole($request['role']);

    return response()->json(
      new UserResource($request),
      201
    );
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = $this->userModel->find($id);
    return response()->json(
      new UserResource($user),
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
  public function update(UserUpdateRequest $request, User $user)
  {
    $user->update([
      'name' => $request['name'],
      'email' => $request['email'],
      'password' => Hash::make($request['password']),
    ]);

    $user->syncRoles($request["role"]);
    
    return response()->json(
      new UserResource($user), 
      200
    );
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    $user->delete();

    return response()->json([
      'message' => 'User deleted'
    ], 200);
  }
}
