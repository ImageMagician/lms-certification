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

    public mixed $module_id;
    public mixed $answer;
//    public mixed $q_id;
    public mixed $user_id;
}
