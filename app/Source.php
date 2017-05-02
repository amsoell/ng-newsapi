<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{

    protected $fillable = [ 'id', 'category', 'description', 'url', 'language', 'country', 'NG_Description', 'NG_Review' ];
    protected $casts = ['id' => 'string'];

    public function logos() {
        return $this->hasMany(SourceLogo::class);
    }

    public function sorts() {
        return $this->hasMany(SourceSort::class);
    }

}
