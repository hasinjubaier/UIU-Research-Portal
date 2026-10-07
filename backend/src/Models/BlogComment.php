<?php

namespace App\Models;

class BlogComment extends BaseModel
{
    protected string $table = 'blog_comments';
    protected array $fillable = ['post_id', 'user_id', 'parent_id', 'comment'];
}
