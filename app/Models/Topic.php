<?php

namespace App\Models;

class Topic extends Model
{
    /*
        user_id —— 文章的作者，我们不希望文章的作者可以被随便指派；
        last_reply_user_id —— 最后回复的用户 ID，将有程序来维护；
        order —— 文章排序，将会是管理员专属的功能；
        reply_count —— 回复数量，程序维护；
        view_count —— 查看数量，程序维护；
     */
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    /*
     * 有了以上的关联设定，后面开发中我们可以很方便地通过 $topic->category、$topic->user 来获取到话题对应的分类和作者
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
     * 这里我们使用了 Laravel 本地作用域 。本地作用域允许我们定义通用的约束集合以便在应用中复用
     * 要定义这样的一个作用域，只需简单在对应 Eloquent 模型方法前加上一个 scope 前缀，作用域总是返回 查询构建器。
     * 一旦定义了作用域，则可以在查询模型时调用作用域方法。在进行方法调用时不需要加上 scope 前缀。
     */
    public function scopeWithOrder($query,$order){
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order){
            case 'recent':
                $query = $this->recent();
                break;
            default:
                $query = $this->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('user','category');
    }

    public function scopeRecentReplied($query){
        //当话题有新回复时，我们将编写逻辑来更新话题模型的reply_count属性
        //此时会自动触发框架对数据模型updated_at时间戳的更新
        return $query->orderBy('updated_at','desc');
    }

    public function scopeRecent($query) {
        // 按照创建时间排序
        return $query->orderBy('created_at','desc');
    }
}
