<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{

    use SoftDeletes;

    /**
     *
     * @var type 
     */
    protected $table = 'user_group';

    /**
     *
     * @var type 
     */
    protected $fillable = [
        'user_id',
        'group_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
