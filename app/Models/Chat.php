<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *      schema="Chat",
 *      required={},
 *      @OA\Property(
 *          property="user_id",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="sender_id",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="string",
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */class Chat extends Model
{
    use HasFactory;    public $table = 'chats';

    public $fillable = [
        'user_id',
        'sender_id'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'sender_id' => 'integer'
    ];

    public static array $rules = [

    ];

    public function userId(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')
            ->select(['id', 'name', 'surname','email', 'image', 'username', 'bio']);
    }

    public function senderId(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')
            ->select(['id', 'name', 'surname','email', 'image', 'username', 'bio']);
    }

}
