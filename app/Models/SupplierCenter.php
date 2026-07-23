<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierCenter extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'supplier_centers';

    protected $fillable = ['code', 'name'];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'supplier_center_id');
    }
}
