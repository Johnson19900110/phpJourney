<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * 按文章查找评论
     * @param $query
     * @param $post_id
     * @return mixed
     */
    public function scopeOfPost($query, $post_id)
    {
        if(intval($post_id) > 0) {
            return $query->where('post_id', $post_id);
        }
        return $query;
    }

    /**
     * 搜索评论
     * @param $query
     * @param $content
     * @return mixed
     */
    public function scopeOfContent($query, $content)
    {
        if(!empty($content)) {
            return $query->where('content', 'like', '%' . $content . '%');
        }
        return $query;
    }

    /**
     * 获取所属文章标题
     * @return mixed
     */
    public function posts()
    {
        return $this->belongsTo(Post::class, 'post_id')->select('title');
    }
}
