<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'uploads';
    protected $fillable = ['file_name'];

    protected $appends = [
        'file_full_path'
    ];


    public function getFileFullPathAttribute()
    {
        return $this->file_name ?  asset($this->file_name) : '';
    }
}
