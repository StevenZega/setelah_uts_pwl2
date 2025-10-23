<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'supplier_name',
        'pic_supplier',
    ];

    public function get_supplier(){
        return $this->select('*');
    }
}