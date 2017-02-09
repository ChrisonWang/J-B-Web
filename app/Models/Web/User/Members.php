<?php

namespace App\Models\Web\User;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    public $table = 'user_members';

    public $primaryKey = 'id';

    public $timestamps = false;
}
