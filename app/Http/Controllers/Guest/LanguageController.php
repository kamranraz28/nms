<?php
namespace App\Http\Controllers\Guest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App;

class LanguageController extends Controller
{
    
    public function switchLang($lang)
    {

        //return App::getLocale();
        //return Config::get('languages');
        //return $lang;
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
        }

        //Session::get('applocale');
        //return App::getLocale();

        //return __('site.footer.title2');

        //return Session::get('applocale');
        return Redirect::back();
    }
}