<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormResponse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'submitted_at', 'cagar_budaya', 'nama_petugas', 'kegiatan',
        'dokumentasi_kegiatan', 'jumlah_pengunjung', 'wisatawan_nusantara',
        'pelajar_mahasiswa', 'wisatawan_mancanegara', 'tamu_dinas',
        'dokumentasi_kunjungan', 'temuan_kerusakan', 'deskripsi_kerusakan',
        'dokumentasi_kerusakan', 'kondisi_keamanan_situs',
        'catatan_kondisi_keamanan', 'raw_payload',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'raw_payload' => 'array',
    ];
}