<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servers extends Model
{
    use HasFactory;

    protected $table = 'servers';

    protected $fillable = [
        'name',
        'location',
        'description',
        'plan',
        'ssh_keys',
        'password',
        'user',
        'project',
        'backup_settings',
        'ip_types',
        'additional_ip_count',
        'additional_ipv6_count',
        'additional_disks',
        'os',
        'compute_resource',
        'primary_ip',
        'solus_server_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user','solusvm_uid');
    }
}
