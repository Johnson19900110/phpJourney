<?php

namespace App;

use App\Libraries\EsSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Post extends Model
{
    //
    use SoftDeletes, Searchable, EsSearchable;

    /**
     * 获取对应的分类category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * 获取对应标签tags
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class,'posts_tags');
    }

    /**
     * 按照分类查找
     * @param $query
     * @param $category_id
     * @return mixed
     */
    public function scopeOfCategory($query, $category_id)
    {
        if (intval($category_id) > 0) {
            return $query->where('category_id', $category_id);
        }
        return $query;
    }

    /**
     * 按文章标题模糊查询
     * @param $query
     * @param $title
     * @return mixed
     */
    public function scopeOfTitle($query, $title)
    {
        if(!empty($title)) {
            return $query->where('title', 'like', '%' . $title . '%');
        }

        return $query;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
