<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
 protected $fillable = [
 'name', 'member_code', 'email',
 'phone', 'address', 'status', 'joined_at',
 ];
}

