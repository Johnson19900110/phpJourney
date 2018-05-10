<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/23
 * Time: 00:09
 */

namespace App\Providers;


use App\Http\ViewComposers\Navigation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    public function boot(){
        View::composer('widgets.navigation', Navigation::class);
    }

    public function register(){

    }

}
