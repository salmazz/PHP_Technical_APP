<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Todo",
 *     type="object",
 *     title="Todo",
 *     description="A Todo object",
 *     @OA\Property(property="id", type="integer", description="ID of the todo"),
 *     @OA\Property(property="title", type="string", description="Title of the todo"),
 *     @OA\Property(property="description", type="string", description="Description of the todo"),
 *     @OA\Property(property="status", type="string", description="Status of the todo", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user who created the todo"),
 * )
 */
class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'image', 'user_id', 'status'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ARCHIVED = 'archived';

    // إعداد الحالات الممكنة
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_ON_HOLD,
            self::STATUS_CANCELED,
            self::STATUS_ARCHIVED,
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
