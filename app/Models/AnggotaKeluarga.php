<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    protected $table = 'fm_anggota_keluarga';

    protected $fillable = [
        'rumah_tangga_id',
        'nama',
        'nik',
        'hdkrt',
        'nuk',
        'hdkk',
        'kelamin',
        'status_perkawinan',
        'status_pekerjaan',
        'jenis_pekerjaan',
        'sub_jenis_pekerjaan',
        'pendidikan_terakhir',
        'pendapatan_per_bulan',
    ];

    public function rumahTangga()
    {
        return $this->belongsTo(RumahTangga::class);
    }

    // ===== ACCESSOR UNTUK SEMUA FIELD KODE =====

    public function getFormattedNikAttribute(): string
    {
        $nik = preg_replace('/[^0-9]/', '', (string)($this->nik ?? ''));
        if (strlen($nik) === 16) {
            return substr($nik, 0, 6) . ' ' . substr($nik, 6, 6) . ' ' . substr($nik, 12, 4);
        }
        return $nik ?: '-';
    }

    public function getKelaminTextAttribute(): string
    {
        return match ($this->kelamin ?? '') {
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
            default => '-',
        };
    }

    public function getHdkrtTextAttribute(): string
    {
        return match ($this->hdkrt ?? '') {
            '1' => 'Kepala Keluarga',
            '2' => 'Istri/Suami',
            '3' => 'Anak',
            '4' => 'Menantu',
            '5' => 'Cucu',
            '6' => 'Orang Tua/Mertua',
            '7' => 'Pembantu RT',
            '8' => 'Lainnya',
            default => '-',
        };
    }

    public function getHdkkTextAttribute(): string
    {
        return $this->getHdkrtTextAttribute(); // Nilai sama persis
    }

    public function getStatusPerkawinanTextAttribute(): string
    {
        return match ($this->status_perkawinan ?? '') {
            '1' => 'Belum Kawin',
            '2' => 'Kawin',
            '3' => 'Cerai Hidup',
            '4' => 'Cerai Mati',
            default => '-',
        };
    }

    public function getStatusPekerjaanTextAttribute(): string
    {
        return match ($this->status_pekerjaan ?? '') {
            '1' => 'Bekerja',
            '2' => 'Ibu Rumah Tangga',
            '3' => 'Bersekolah',
            '4' => 'Tidak/Belum Bekerja',
            '5' => 'Lainnya',
            default => '-',
        };
    }

    public function getJenisPekerjaanTextAttribute(): string
    {
        return match ($this->jenis_pekerjaan ?? '') {
            '1' => 'PNS/TNI/POLRI',
            '2' => 'Karyawan/Honorer',
            '3' => 'Wiraswasta',
            '4' => 'Lainnya',
            default => '-',
        };
    }

    public function getSubJenisPekerjaanTextAttribute(): string
    {
        return match ($this->sub_jenis_pekerjaan ?? '') {
            '1' => 'Aparatur Pemerintah',
            '2' => 'Tenaga Ahli/Profesional',
            '3' => 'Tenaga Kerja Harian',
            '4' => 'Pengusaha/Wira Usaha',
            '5' => 'Lainnya',
            default => '-',
        };
    }

    public function getPendidikanTerakhirTextAttribute(): string
    {
        return match ($this->pendidikan_terakhir ?? '') {
            '1' => 'Tidak/Belum Sekolah',
            '2' => 'Tamat SD/Sederajat',
            '3' => 'Tamat SMP/Sederajat',
            '4' => 'Tamat SMA/Sederajat',
            '5' => 'Tamat Perguruan Tinggi',
            '6' => 'Tidak Pernah Sekolah',
            default => '-',
        };
    }

    public function getPendapatanPerBulanTextAttribute(): string
    {
        return match ($this->pendapatan_per_bulan ?? '') {
            '1' => '> Rp 500.000',
            '2' => '> Rp 1.000.000',
            '3' => '> Rp 2.000.000',
            '4' => '> Rp 4.000.000',
            '5' => 'Tidak Ada Pendapatan',
            default => '-',
        };
    }
}
