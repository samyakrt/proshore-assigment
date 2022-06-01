<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'department_id',
        'role_id',
        'manager_id',
        'join_date'
    ];

    protected $casts = [
        'join_date' =>'date'
    ];

    protected $appends = [
        'joined'
    ];



    public function getJoinedAttribute(): string
    {
        return  $this->join_date ? $this->join_date->diffForHumans() : '';
    }

    /**
     * Get the role that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Self::class, 'manager_id');
    }

    /**
     * Get the department that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the role that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


}
