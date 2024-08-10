<?php

namespace App\Models; // Tambahkan namespace yang sesuai

use Illuminate\Database\Eloquent\Model;

class Sekretaris extends Model
{

    protected $table = 'sekretaris';
    protected $primaryKey = 'id_sekretaris';
    protected $fillable = [
        'presensi_id', 'anggota_id',
    ];

    // Jika diperlukan, definisikan relasi ke presensi di sini
    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'id_presensi');
    }

    // Jika diperlukan, definisikan relasi ke anggota di sini
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
