<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\InvoicePaid;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(\App\Post::class, 'user_id', 'id');
    }

    /*
     * 我的粉丝
     */
    public function fans()
    {
        return $this->hasMany(\App\Fan::class, 'star_id', 'id');
    }
    /**
     * @brief 添加关注
     * @param unknown $uid
     */
    public function dofan($uid)
    {
        $fan =new Fan();
        $fan->star_id=$uid;
        $fan->fan_id=\Auth::id();
        $this->stars()->save($fan);
    }
    /**
     * @brief 取消关注
     * @param unknown $uid
     */
    public function dounfan($uid)
    {
        $fan=new Fan();
        $fan->star_id=$uid;
        $fan->fan_id=\Auth::id();
        $this->stars()->delete($fan);
    }
    /*
     * 当前这个人是否被uid粉了
     */
    public function hasFan($uid)
    {
        return $this->fans()->where('fan_id', $uid)->count();
    }

    public function hasStar($uid)
    {
        return $this->stars()->where('star_id',$uid)->count();
    }
    /*
     * 我粉的人
     */
    public function stars()
    {
        return $this->hasMany(\App\Fan::class, 'fan_id', 'id');
    }
    
    /*
     * 我收到的通知
     */
    public function notices()
    {
        return $this->belongsToMany(\App\Notice::class, 'user_notice', 'user_id', 'notice_id')->withPivot(['user_id', 'notice_id']);
    }
    
    /**
     * 邮件频道的路由
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }
    /*
     * 增加通知
     */
    public function addNotice($notice)
    {
        $this->notify(new InvoicePaid($notice));
        return $this->notices()->save($notice);
    }

    /*
     * 减少通知
     */
    public function deleteNotice($notice)
    {
        return $this->notices()->detach($notice);
    }

    public function getAvatarAttribute($value)
    {
        if (empty($value)) {
            return '/storage/231c7829cbd325d978898cec389b3f65/egwV7WNPQMSPgMe7WmtjRN7bGKcD0vBAmpRrpLlI.jpeg';
        }
        return $value;
    }
}
