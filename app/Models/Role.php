<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;


class Role extends SpatieRole
{
    use HasFactory;
    
    public const SUPER_ADMIN  = 'super_admin';
    public const OWNER        = 'owner';
    public const KASIR        = 'kasir';
    public const STAFF        = 'staff';

    public static function getRoles(): array
    {
      try {
        return array_values(static::lastConstants());
      } catch (\ReflectionException $exception) {
        return [];
      }
    }

    static function lastConstants()
    {
      $parentConstants = static::getParentConstants();
  
      $allConstants = static::getConstants();
  
      return array_diff($allConstants, $parentConstants);
    }
    
  static function getConstants()
  {
    $rc = new \ReflectionClass(get_called_class());

    return $rc->getConstants();
  }

  static function getParentConstants()
  {
    $rc = new \ReflectionClass(get_parent_class(static::class));
    return $rc->getConstants();
  }
}

