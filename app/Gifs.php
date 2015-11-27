<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gifs extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gifs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function images()
    {
        return $this->hasMany('\App\Images', 'gif_id');
    }
}
