<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    protected $table = 'prompts';
    protected $fillable = [
        'prompt',
    ];

    public function images(){
        return $this->hasMany(Image::class);
    }
}
