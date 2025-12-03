<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TicketStatusEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory, InteractsWithMedia;

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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
