<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
  private $pagination;

  public function __construct($resource)
  {
    $this->pagination = [
      'total' => $resource->total(),
      'perPage' => $resource->perPage(),
      'currentPage' => $resource->currentPage(),
      'lastPage' => $resource->lastPage()
    ];

    $resource = $resource->getCollection();
    parent::__construct($resource);
  }

  public $collects = PostResource::class;

  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'data' => $this->collection,
      'meta' => $this->pagination,
    ];
  }
}
