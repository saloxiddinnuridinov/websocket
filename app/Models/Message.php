<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

/**
 * @OA\Schema(
 *      schema="Message",
 *      required={"text"},
 *      @OA\Property(
 *          property="chat_id",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="text",
 *          description="",
 *          readOnly=false,
 *          nullable=false,
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="is_read",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="boolean",
 *      ),
 *      @OA\Property(
 *          property="type",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="file_url",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="token",
 *          description="",
 *          readOnly=false,
 *          nullable=true,
 *          type="string",
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
 */class Message extends Model
{
    use HasFactory;

    public $table = 'messages';

    public $fillable = [
        'chat_id',
        'text',
        'is_read',
        'type',
        'file_url',
        'token'
    ];
    protected $casts = [
        'chat_id' => 'integer',
        'text' => 'string',
        'is_read' => 'boolean',
        'type' => 'string',
        'file_url' => 'string',
        'token' => 'string'
    ];

    public static array $rules = [
        'text' => 'required'
    ];

    public function senderId()
    {
        return $this->belongsTo(User::class, 'sender_id')
            ->select(['id', 'name', 'surname','email', 'image', 'username', 'bio']);
    }
}
