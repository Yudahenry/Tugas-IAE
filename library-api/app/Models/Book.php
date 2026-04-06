<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
 protected $fillable = [
 'title', 'author', 'isbn', 'category',
 'publisher', 'year', 'stock', 'description',
 ];
}
