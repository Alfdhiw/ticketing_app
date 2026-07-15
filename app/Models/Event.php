<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'lokasi',
        'gambar',
        'tanggal_waktu',
    ];

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    public function getStatusAttribute()
    {
        if (! $this->tanggal_waktu instanceof Carbon) {
            return 'Upcoming';
        }

        $now = Carbon::now();
        $eventTime = Carbon::parse($this->tanggal_waktu);

        if ($eventTime->gt($now)) {
            return 'Upcoming';
        }

        if ($eventTime->lte($now) && $eventTime->gte($now->copy()->subHours(3))) {
            return 'Ongoing';
        }

        return 'Completed';
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->gambar)) {
            return asset('images/konser.jpg');
        }

        if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
            return $this->gambar;
        }

        if (Storage::disk('public')->exists($this->gambar)) {
            return asset('storage/' . $this->gambar);
        }

        return asset('images/konser.jpg');
    }

    public function hasSales()
    {
        return $this->orders()->exists();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_waktu', '>', Carbon::now());
    }

    public function scopeOngoing($query)
    {
        $now = Carbon::now();

        return $query->where('tanggal_waktu', '<=', $now)
            ->where('tanggal_waktu', '>=', $now->copy()->subHours(3));
    }

    public function scopeCompleted($query)
    {
        return $query->where('tanggal_waktu', '<', Carbon::now()->copy()->subHours(3));
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function eventStatusHistories()
    {
        return $this->hasMany(EventStatusHistory::class);
    }
}
