<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class choices extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'cid';

    public function rQuestions(){
        return $this->belongsTo("App\questions","qid","qid");
    }
    public function choice()
    {
        return $this->belongsToMany('App\questions');
    }

}
