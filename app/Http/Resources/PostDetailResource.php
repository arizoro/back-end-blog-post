<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image,
            'content' => $this->news_content,
            'created_at' => date_format($this->created_at,"Y/m/d"),
            'author_id' => $this->author_id,
            'author' => $this->whenLoaded('author'),
            'comments' => $this->whenLoaded('comments', function()
            {
                return collect($this->comments)->each(function ($comment)
                {
                    return $comment->loadMissing('comentator:id,username,firstname,lastname');
                });
            }),

            'total_comments' => count($this->comments)
        ];
    }
}
