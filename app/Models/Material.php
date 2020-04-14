<?php


namespace App\Models;

use App\Traits\ParseTimeStamp;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;

class Material extends Model
{
    use Exportable;
    use ParseTimeStamp;

    protected $fillable = [
        'name',
        'amount',
        'unit',
    ];
}