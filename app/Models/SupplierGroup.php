<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierGroup extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'supplier_groups';

    protected $fillable = ['code', 'name', 'description'];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'supplier_group_id');
    }
}
