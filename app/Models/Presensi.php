<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{

    protected $table = 'presensi';
    protected $primaryKey = 'id_presensi';

    protected $fillable = [
        'kode_acak', 'id_admin', 'time_start', 'time_end', 'event_name', 'description', 'id_organisasi'
    ];

    // Jika diperlukan, definisikan relasi ke admin di sini
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Jika diperlukan, definisikan relasi ke detail presensi di sini
    public function detailSekretaris()
    {
        return $this->hasMany(Sekretaris::class);
    }

    public function sekretaris()
    {
        return $this->hasMany(Sekretaris::class, 'id_presensi');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}
