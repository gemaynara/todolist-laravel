<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const PENDING = 'Pendente';
    public const WORKING = 'Em Andamento';
    public const FINISHED = 'Finalizado';

    use HasFactory;

    public $timestamps = true;
    protected $table = 'tasks';
    protected $fillable = [
        'user_id',
        'name',
        'status',
        'finished_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
