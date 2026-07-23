<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'area'; // nama tabel di database (tanpa schema kalau pakai search_path)
    protected $fillable = ['kategori_id', 'nama']; // kolom yang bisa diisi mass-assignment

    public function kategori()
    {
        return $this->hasOne(Lookup::class, 'lookupvalue', 'kategori_id')
            ->where('lookupkey', 'kategori_area');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'area_id', 'id');
    }
}
