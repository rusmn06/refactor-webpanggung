<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumahTangga extends Model
{
    protected $table = 'fm_rumah_tangga';

    protected $fillable = [
        'user_id',
        'user_sequence_number',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'rt',
        'rw',
        'tgl_pembuatan',
        'nama_pendata',
        'nama_responden',
        'jart',
        'jart_ab',
        'jart_tb',
        'jart_ms',
        'jpr2rtp',
        'verif_tgl_pembuatan',
        'verif_nama_pendata',
        'ttd_pendata',
        'status_validasi',
        'admin_tgl_validasi',
        'admin_nama_kepaladusun',
        'admin_ttd_pendata',
        'admin_catatan',
    ];

    protected $casts = [
        'tgl_pembuatan'       => 'date',
        'verif_tgl_pembuatan' => 'date',
        'admin_tgl_validasi'  => 'date',
    ];

    // ===== RELASI =====

    // Satu RumahTangga punya banyak AnggotaKeluarga
    public function anggotaKeluarga()
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    // Satu RumahTangga dimiliki satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===== ACCESSOR =====
    // Accessor = properti virtual yang diformat otomatis
    // Dipanggil dengan: $item->status_validasi_text

    public function getStatusValidasiTextAttribute(): string
    {
        return match ($this->status_validasi) {
            'pending'   => 'Pending',
            'validated' => 'Disetujui',
            'rejected'  => 'Ditolak',
            default     => ucfirst($this->status_validasi ?? '-'),
        };
    }

    public function getJpr2rtpTextAttribute(): string
    {
        return match ($this->attributes['jpr2rtp'] ?? '') {
            '1' => 'Pendapatan di Atas Rp 500.000',
            '2' => 'Pendapatan di Atas Rp 1.000.000',
            '3' => 'Pendapatan di Atas Rp 2.000.000',
            '4' => 'Pendapatan di Atas Rp 4.000.000',
            '5' => 'Tidak Ada Pendapatan',
            default => 'Tidak Diketahui',
        };
    }
}
