<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // Attributs autorisés pour l'insertion dans la base de données
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Attributs à cacher (notamment le mot de passe et le token de session)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts pour garantir que certains attributs sont convertis dans un format spécifique
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Le mot de passe est haché automatiquement lors de la création
        'password' => 'hashed',
    ];

    // Identifier l'utilisateur dans JWT
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retourne la clé primaire de l'utilisateur
    }

    // Personnaliser les "claims" du JWT si nécessaire
    public function getJWTCustomClaims(): array
    {
        return [
            // Vous pouvez ajouter ici des informations supplémentaires
            // 'role' => $this->role,
        ];
    }

    // Vous pouvez également ajouter d'autres méthodes utiles si nécessaire, par exemple, un scope pour récupérer un utilisateur actif
}
