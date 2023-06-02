<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    Protected $fillable=[
        'name',
        'email',
        'phone',
        'company',
        'education',
        'hobby',
        'gender',
        'experience',
        'message',
        'file',
    ];

    public function educationname()
    {
        return $this->belongsTo(Education::class,'education');
    }
    public function companyname()
    {
        return $this->belongsTo(Company::class,'company');
    }
}
