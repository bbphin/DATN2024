<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailContent extends Model
{
    use HasFactory;
    public $table='email_contents';
    public $fillable=[
        'email_type',
        'content'
    ];
}
