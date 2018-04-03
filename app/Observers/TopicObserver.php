<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }
    /*
     * Eloquent 模型会触发许多事件（Event），我们可以对模型的生命周期内多个时间点进行监控： creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored。事件让你每当有特定的模型类在数据库保存或更新时，执行代码。当一个新模型被初次保存将会触发 creating 以及 created 事件。
     * 如果一个模型已经存在于数据库且调用了 save 方法，将会触发 updating 和 updated 事件。在这两种情况下都会触发 saving 和 saved 事件。
     */
    public function saving(Topic $topic)
    {
        // XSS 过滤
//        $topic->body = clean($topic->body, 'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        // app() 允许我们使用 Laravel 服务容器 ，此处我们用来生成 SlugTranslateHandler 实例。
        if(!$topic->slug){
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}