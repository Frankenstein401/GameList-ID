<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
