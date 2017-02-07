<?php

namespace App\Models\Manage\User;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    public $table = 'user_manager';

    public $primaryKey = 'id';

    public $timestamps = false;
}
