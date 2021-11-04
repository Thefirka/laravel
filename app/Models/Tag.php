<?php

namespace App\Models;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
    public function scopeFilter(Builder $builder, QueryFilters $filter)
    {
        return $filter->apply($builder);
    }
}
