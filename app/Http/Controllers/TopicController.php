<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;

class TopicController extends Controller
{
    /*
     * topic详情页
     */
    public function show(Topic $topic)
    {
        $topicPostCnt=Topic::withCount('postTopic')->find($topic->id);
        //专题的文章列表，按创建时间倒序获取前10项
        $posts = $topic->posts()->orderBy('created_at', 'desc')->with(['user'])->take(10)->get();
        $me = \Auth::user();
        //属于我的文章，但是未投稿
        $myposts = \App\Post::authorBy(\Auth::id())->topicNotBy($topic->id)->get();

        return view('topic/show', compact('topic', 'posts', 'myposts','topicPostCnt'));
    }

    /*
     * 投稿
     */
    public function submit(Topic $topic)
    {
        $this->validate(request(),[
            'post_ids' => 'required|array'
        ]);

        // 确认这些post都是属于当前用户的
        $posts = \App\Post::find(request(['post_ids']));
        foreach ($posts as $post) {
            if ($post->user_id != \Auth::id()) {
                return back()->withErrors(array('message' => '没有权限'));
            }
        }


        $post_ids = request('post_ids');
        $topic_id = $topic->id;
        foreach ($post_ids as $post_id){
            \App\PostTopic::firstOrCreate(compact('topic_id', 'post_id'));
        }
        return back();
    }
}
