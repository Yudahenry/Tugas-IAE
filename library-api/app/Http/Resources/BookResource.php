<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
 return [
 'id' => $this->id,
 'title' => $this->title,
 'author' => $this->author,
 'isbn' => $this->isbn,
 'category' => $this->category ?? '-',
 'publisher' => $this->publisher ?? '-',
 'year' => $this->year,
 'stock' => $this->stock,
 'description' => $this->description,
 'created_at' => $this->created_at->toDateTimeString(),
 ];
}

}
