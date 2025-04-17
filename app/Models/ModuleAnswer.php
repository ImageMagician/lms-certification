<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class ModuleAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'answer',
        'q_id',
        'user_id'
    ];
}
