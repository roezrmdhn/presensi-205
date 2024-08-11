<?php

namespace App\Models; // Tambahkan namespace yang sesuai


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan',
        'id_organisasi',
        'departemen',
        'address',
        'phone',
        'more',
        'role',
        'password',
        'foto'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
