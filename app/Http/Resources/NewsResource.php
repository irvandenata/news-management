<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'=>$this->title,
            'slug'=>$this->slug,
            'content'=> $this->content,
            'category'=> $this->category->name,
            'category_slug'=> $this->category->slug,
            'author'=> $this->author->name,
            'image'=> $this->image,
            'comments'=> CommentResource::collection($this->comments),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
