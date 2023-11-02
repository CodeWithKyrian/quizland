<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use Filterable, HasFactory;

    protected $fillable = ['body'];

    public $timestamps = false;

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->inRandomOrder();
    }

}
