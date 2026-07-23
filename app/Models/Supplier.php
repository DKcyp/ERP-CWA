<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'suppliers';

    protected $fillable = [
        'supplier_code',
        'name',
        'supplier_group_id',
        'supplier_center_id',
        'phone',
        'email',
        'address',
        'term_of_payment',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function supplierGroup()
    {
        return $this->belongsTo(SupplierGroup::class, 'supplier_group_id');
    }

    public function supplierCenter()
    {
        return $this->belongsTo(SupplierCenter::class, 'supplier_center_id');
    }
}
