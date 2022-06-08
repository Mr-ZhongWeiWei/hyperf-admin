<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property string $path
 * @property string $component
 * @property string $redirect
 * @property string $meta
 * @property int $status
 * @property int $is_show
 * @property string $access
 * @property string $logic
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $sort
 */
class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'status' => 'integer', 'is_show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'sort' => 'integer'];

    /**
     * @param array $where
     * @param bool $unsetMeta
     * @param string[] $field
     * @return mixed[]
     * @author: Zhong Weiwei
     * @Date: 11:44  2022/3/20
     */
    public static function getMenus($where = [], $unsetMeta = true, $field = ['id','name','path','component','redirect','status','is_show','meta','parent_id','access'])
    {
        $menus  =   self::query()->where($where)->orderBy('sort')->get($field)->toArray();
        foreach ($menus as &$item){
            $item['meta']   =   json_decode($item['meta'],true);
            if ($unsetMeta){
                $item['title']  =   $item['meta']['title'];
                $item['icon']   =   $item['meta']['icon'];
                unset($item['meta']);
            }
        }
        return $menus;
    }
}
