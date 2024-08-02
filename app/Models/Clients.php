<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'user_id',
        'billing_token',
        'billing_user_id',
        'language_id',
        'limit_group_id',
        'roles',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
