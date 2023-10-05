<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function author()
{
    return $this->belongsTo(User::class, 'author_id', 'id');
}

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

}
