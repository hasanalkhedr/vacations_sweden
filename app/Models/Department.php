<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'manager_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    public function scopeSearch($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('name', 'like', '%' . request('search') . '%')
                ->orwhereHas('manager', function ($q) {
                    $q->where('first_name', 'like', '%' . request('search') . '%')
                        ->orwhere('last_name', 'like', '%' . request('search') . '%');
                });;
        };
    }

    public function employees() {
        return $this->hasMany(Employee::class);
    }

    public function manager() {
        return $this->hasOne(Employee::class, 'id' , 'manager_id');
    }
}
