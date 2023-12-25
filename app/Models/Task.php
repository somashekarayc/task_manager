<?php

namespace App\Models;

use AfaanBilal\LaravelHasUUID\HasUUID;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use HasUUID;

    protected $casts = [
        'status' => TaskStatus::class,
    ];
}
