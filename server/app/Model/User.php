<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;
use Qbhy\HyperfAuth\AuthAbility;
use Qbhy\HyperfAuth\Authenticatable;
/**
 * @property int $id
 * @property string $user_name
 * @property string $nickname
 * @property string $login_name
 * @property string $password
 * @property string $sex
 * @property string $mobile
 * @property string $photo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property int $status
 * @property string $user_type
 * @property string $uuid
 * @property string $organizeid
 * @property-read \Hyperf\Database\Model\Collection|UserRole[] $userRole
 */
class User extends Model implements Authenticatable
{
    use AuthAbility;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_name','nickname','password','sex','mobile','photo','last_login_time','last_login_ip','status','user_type','uuid','organizeid'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'status' => 'integer'];
    public static function retrieveById($key) : ?Authenticatable
    {
        $user = self::query()->find($key, ['id', 'user_name', 'nickname', 'login_name', 'sex', 'mobile', 'photo']);
        $permission = [];
        foreach (array_column($user->userRole->toArray(), 'access') as $item) {
            $item && ($permission = array_merge($permission, explode(',', $item)));
        }
        $user->permission = AccessRule::query()->whereIn('id', array_unique($permission))->where('type', 1)->pluck('rule')->toArray();
        return $user;
    }
    /**
     * 关联用户角色
     * @return HasMany|null
     * @author: Zhong Weiwei
     * @Date: 14:53  2022/3/19
     */
    public function userRole() : ?HasMany
    {
        return $this->hasMany(UserRole::class, 'uid', 'id')->join('role', 'user_role.role_id', '=', 'role.id')->select(['role.*']);
    }
}
