<?php 
/**
 * Post Model
 * 
 * PHP 7.3
 */
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Post properties
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get a list of posts
     * 
     * @return object
     */
    public function findPosts()
    {
        return self::all();
    }
}