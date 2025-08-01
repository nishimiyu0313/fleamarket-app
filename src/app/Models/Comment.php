<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id', 'content'];

    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function item()
    {
        return $this->belongsTo(item::class);
    }
}
