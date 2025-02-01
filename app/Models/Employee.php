<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MBarlow\Megaphone\HasMegaphone;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasMegaphone, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'nb_of_days',
        'overtime_minutes',
        'department_id',
        'weekdays_off',
        'profile_photo',
        'bypass_officers', // Add this line
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weekdays_off' => 'array'
    ];

    protected $attributes = [
        'nb_of_days' => 30,
        'overtime_minutes' => 0,
        'bypass_officers' => false, // Add this line
    ];

    public function scopeSearch($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('first_name', 'like', '%' . request('search') . '%')
                ->orwhere('last_name', 'like', '%' . request('search') . '%')
                ->orwhereHas('roles', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                })
                ->orwhereHas('department', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
        };
    }


    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function leaves() {
        return $this->hasMany(Leave::class);
    }

    public function overtimes() {
        return $this->hasMany(Overtime::class);
    }

    public function allRoles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id');
    }

    public function getRoleNamesCustom() {
        $roles = [];
        foreach ($this->getRoleNames() as $name) {
            if($name == "Employee" && $this->is_supervisor) {
                $roles [] = ucfirst(__("Supérieure Hiérarchique"));
            }
            else {
                $roles [] = ucfirst(__($name));
            }
        }
        return $roles;
    }

    public function getRoleIds() {
        $role_ids = [];
        foreach($this->allRoles as $role) {
            $role_ids [] = $role->id;
        }
        return $role_ids;
    }
}
