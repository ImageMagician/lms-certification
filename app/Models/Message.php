<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class Message extends Model
{
    use HasFactory;

    public mixed $user_id;
    public mixed $admin_id;
    /**
     * @var mixed|string
     */
    public mixed $message;
}
