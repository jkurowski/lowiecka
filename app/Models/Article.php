<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title',
        'slug',
        'content_entry',
        'content',
        'file',
        'file_webp',
        'file_facebook',
        'file_alt',
        'meta_title',
        'meta_description',
        'meta_robots',
        'status',
        'sort',
        'posted_at'
    ];
}
