<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppList extends Model
{
    protected $fillable = [
        'app_slug',
        'app_name',
        'logo',
    ];

    public function appTypes()
    {
        return $this->hasMany(AppType::class);
    }


}
