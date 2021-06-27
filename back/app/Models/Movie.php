<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected function getUpdatedAtAttribute($value): string {
        return Carbon::parse($value)->format('d.m.Y H:i:s');
    }

    protected function getCreatedAtAttribute($value): string {
        return Carbon::parse($value)->format('d.m.Y H:i:s');
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
