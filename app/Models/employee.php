<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'phone', 'image', 'position', 'division_id'
    ];

    protected $primaryKey = 'id';

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
