<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppType extends Model
{
    protected $fillable = [
        'app_list_id',
        'store_type',
        'url',
    ];

    public function appList()
    {
        return $this->belongsTo(AppList::class);
    }
}
