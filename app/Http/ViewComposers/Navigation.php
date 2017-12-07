<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:54
 */

namespace App\Http\ViewComposers;


use App\Category;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\View;

class Navigation
{
    /**
     * 绑定数据到视图.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $categories = Category::get();
        
        $view->with('categories', ($categories));
    }
}