<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @method static updateOrCreate(array $array, array $array1)
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
        'admin_id',
        'note',
    ];
}
