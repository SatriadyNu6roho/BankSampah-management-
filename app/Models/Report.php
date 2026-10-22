<?php

namespace App\Models; // Gunakan namespace App; jika lokasi file kamu ada di app/Report.php

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // Tambahkan kolom-kolom baru di $fillable agar izin simpan/update diberikan oleh Laravel
    protected $fillable = [
        'user_id',
        'jenis_sampah',
        'foto',
        'bobot_kg',
        'poin',
        'saldo_rp',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}