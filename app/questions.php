<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    //
    public $timestamps = false;
    protected $primaryKey = 'qid';

    public function choices(){
        return $this->hasMany("App\choices","qid","qid");
    }

    public function quest()
    {
        return $this->belongsToMany('App\choices',"answers","qid","cid");
    }

}
