<?php

namespace DraperStudio\Friendable\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public $table = 'friendships';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sender()
    {
        return $this->morphTo('sender');
    }

    public function recipient()
    {
        return $this->morphTo('recipient');
    }
}
