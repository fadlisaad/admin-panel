<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'email',
        'url',
    ];

    public $timestamps = true;

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
