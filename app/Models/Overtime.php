<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Overtime extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'date',
        'from',
        'to',
        'hours',
        'objective',
        'date_of_submission',
        'overtime_status',
        'processing_office_role',
        'cancellation_reason',
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

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format(config('app.date_format')),
            set: fn ($value) => Carbon::createFromFormat(config('app.date_format'), $value)->format('Y-m-d'),
        );
    }

    public function scopeSearch($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->whereHas('employee', function ($q) {
                $q->where('first_name', 'like', '%' . request('search') . '%')
                    ->orwhere('last_name', 'like', '%' . request('search') . '%');
            });;
        };
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function processing_officer() {
        return $this->belongsTo(Role::class, 'processing_officer_role');
    }

    public function scopePending($query)
    {
        $query->where('leave_status', 0);
    }

    public function scopeAccepted($query)
    {
        $query->where('overtime_status', 1);
    }

    public function scopeRejected($query)
    {
        $query->where('overtime_status', 2);
    }
}
