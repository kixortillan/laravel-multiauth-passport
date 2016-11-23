<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{

    use SoftDeletes;

    /**
     *
     * @var type
     */
    protected $table = 'groups';

    /**
     *
     * @var type 
     */
    protected $fillable = [
        'name',
        'desc',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 
     * @return type
     */
    public function user()
    {
        return $this->belongsToMany(User::class, 'user_id', 'id')->withTimestamps();
    }

}
