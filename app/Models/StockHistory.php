<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockHistory extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'adjustment_type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'changed_by',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_change' => 'integer',
        'quantity_after' => 'integer',
    ];

    // Adjustment type constants
    const TYPE_PURCHASE = 'purchase';
    const TYPE_SALE = 'sale';
    const TYPE_RETURN = 'return';
    const TYPE_DAMAGE = 'damage';
    const TYPE_LOSS = 'loss';
    const TYPE_CORRECTION = 'correction';
    const TYPE_TRANSFER_IN = 'transfer_in';
    const TYPE_TRANSFER_OUT = 'transfer_out';
    const TYPE_ADJUSTMENT = 'adjustment';

    public static function getAdjustmentTypes(): array
    {
        return [
            self::TYPE_PURCHASE => 'Achat',
            self::TYPE_SALE => 'Vente',
            self::TYPE_RETURN => 'Retour',
            self::TYPE_DAMAGE => 'Endommagé',
            self::TYPE_LOSS => 'Perte',
            self::TYPE_CORRECTION => 'Correction',
            self::TYPE_TRANSFER_IN => 'Transfert entrant',
            self::TYPE_TRANSFER_OUT => 'Transfert sortant',
            self::TYPE_ADJUSTMENT => 'Ajustement',
        ];
    }

    public static function getIncreaseTypes(): array
    {
        return [
            self::TYPE_PURCHASE,
            self::TYPE_RETURN,
            self::TYPE_TRANSFER_IN,
            self::TYPE_CORRECTION, 
        ];
    }

    public static function getDecreaseTypes(): array
    {
        return [
            self::TYPE_SALE,
            self::TYPE_DAMAGE,
            self::TYPE_LOSS,
            self::TYPE_TRANSFER_OUT,
            self::TYPE_CORRECTION, 
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function adjuster()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function isIncrease(): bool
    {
        return in_array($this->adjustment_type, self::getIncreaseTypes())
            ? $this->quantity_change > 0
            : false;
    }

    public function isDecrease(): bool
    {
        return in_array($this->adjustment_type, self::getDecreaseTypes())
            ? $this->quantity_change < 0
            : false;
    }

    public function getAdjustmentTypeLabel(): string
    {
        return self::getAdjustmentTypes()[$this->adjustment_type] ?? $this->adjustment_type;
    }

    public function getAbsoluteQuantityChange(): int
    {
        return abs($this->quantity_change);
    }
}
