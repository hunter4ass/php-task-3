<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
   use HasFactory;

   public $timestamps = false;
   protected $fillable = [
       'name',
       'login',
       'password',
       'phone',
       'email',
       'role',
       'birth_date',
       'policy_number',
       'specialization'
   ];

   protected static function booted(): void
   {
       static::creating(function (User $user) {
           $user->password = self::makeHash($user->password);
       });

       static::updating(function (User $user) {
            if ($user->isDirty('password')) {
                $user->password = self::makeHash($user->password);
            }
       });
   }

   private static function makeHash(string $value): string
   {
       // если пароль уже в md5, повторно не хешируем
       return (bool)preg_match('/^[a-f0-9]{32}$/', $value) ? $value : md5($value);
   }

   //Выборка пользователя по первичному ключу
   public function findIdentity(int $id)
   {
       return self::where('id', $id)->first();
   }

   //Возврат первичного ключа
   public function getId(): int
   {
       return $this->id;
   }

   //Возврат аутентифицированного пользователя
   public function attemptIdentity(array $credentials)
   {
       return self::where(['login' => $credentials['login'],
           'password' => md5($credentials['password'])])->first();
   }
}

