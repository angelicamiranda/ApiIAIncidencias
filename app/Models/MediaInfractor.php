<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaInfractor extends Model
{
    use HasFactory;

    protected $table = 'MEDIA_INFRACTOR';

    protected $fillable = [

        'url',
        'base64',

    ];
}
