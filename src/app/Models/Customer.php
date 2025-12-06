<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Customer model representing a client who submits tickets.
 * 
 * @property int $id
 * @property string $name Customer's full name
 * @property string $phone Phone number in E.164 format
 * @property string $email Email address (unique identifier)
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<Ticket> $tickets
 */
class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    /**
     * Get all tickets submitted by this customer.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
