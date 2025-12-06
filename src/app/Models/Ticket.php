<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TicketStatusEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Ticket model representing a customer support request.
 * 
 * @property int $id
 * @property int $customer_id
 * @property string $subject Ticket subject line
 * @property string $content Message content
 * @property TicketStatusEnum $status Current status (new, in_progress, closed)
 * @property \Illuminate\Support\Carbon|null $replied_at When manager first responded
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at Soft delete timestamp
 * @property-read Customer $customer The customer who submitted this ticket
 */
class Ticket extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'subject',
        'content',
        'status',
        'replied_at'
    ];

    protected $casts = [
        'status' => TicketStatusEnum::class,
        'replied_at' => 'datetime',
    ];

    /**
     * Get the customer who submitted this ticket.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
