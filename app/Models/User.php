<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Carrier;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
	    'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
   

    public function role()
    {
    return $this->belongsTo(Role::class);
    }
    
    public function vendor()
    {
    return $this->hasOne(Vendor::class);
    }

    public function hasRole(string $name)
    {
    return $this->role && $this->role->name === $name;
    }

    public function cartItems()
    {
    return $this->hasMany(CartItem::class);
    }

    public function customer()
    {
    return $this->hasOne(Customer::class);
    }

    
    public function carrier()
    {
        return $this->hasOne(Carrier::class);
    }

    
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function conversationsAsUserOne()
    {
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function conversationsAsUserTwo()
    {
        return $this->hasMany(Conversation::class, 'user_two_id');
    }

    public function conversations()
    {
        return Conversation::where(function ($q) {
            $q->where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
        });
    }


    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

}
