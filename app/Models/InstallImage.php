<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class InstallImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
        'image_cat',
        'image_title',
        'image_ext',
        'image_path'
    ];

    public static function create(array $array)
    {
    }
}
