<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prompt;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = [
        'source',
        'prompt_id'
    ];

    public function prompt(){
        return $this->belongsTo(Prompt::class);
    }

}
