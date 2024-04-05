<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'invitation_token',
        'accepted',
        'accepted_at',
    ];

    // Generate Token
    public function generateToken()
    {
        $token = random_bytes(32);
        $token = bin2hex($token);
        $this->invitation_token = $token;
    }

    public function getRouteKey()
    {
        return 'invitation_token';
    }
}
