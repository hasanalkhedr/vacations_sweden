<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationsGroup extends Model
{
    use HasFactory;

    protected $table = 'notifications_groups';

    protected $fillable = [
        'title',
        'body',
    ];

    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}
