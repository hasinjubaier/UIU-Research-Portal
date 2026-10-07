<?php

namespace App\Models;

class IdeaUpvote extends BaseModel
{
    protected string $table = 'idea_upvotes';
    protected array $fillable = ['idea_id', 'user_id'];
    protected bool $hasSoftDelete = false;
}
