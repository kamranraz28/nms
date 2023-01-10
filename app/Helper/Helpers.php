<?php
//use \NumberFormatter;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;

use App\Models\Lang as ModelsLang;


if (!function_exists('gtrans')) {
    function gtrans($str)
    {
        //$gt = new GoogleTranslate(app()->getLocale()); 
        //return $gt->translate($str);
        return GoogleTranslate::trans($str,app()->getLocale());
    }
}

if (!function_exists('gtrans_backend')) {
    function gtrans_backend($str)
    {
        return GoogleTranslate::trans($str,'bn');
    }
}

if (!function_exists('authGetLocWiseInfo')) {
    function authGetLocWiseInfo()
    {
        // $locWiseAuthUserInfo = Cache::rememberForever('locWiseAuthUserInfo', function () {
        //     return Auth::guard('admin')->user()->load(['forestState','forestDivision','forestRange','forestBeat','userType']);
        // });

        $locWiseAuthUserInfo = Auth::guard('admin')->user()->load(['forestState','forestDivision','forestRange','forestBeat','userType']);
        
        // if($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
        //     return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->forestDivision->{'title_'. app()->getLocale()} ." - ". @$locWiseAuthUserInfo->forestRange->{'title_'. app()->getLocale()} ." - ". @$locWiseAuthUserInfo->forestBeat->{'title_'. app()->getLocale()};
        // } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
        //     return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->forestDivision->{'title_'. app()->getLocale()} ." - ". @$locWiseAuthUserInfo->forestRange->{'title_'. app()->getLocale()};
        // } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
        //     return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->forestDivision->{'title_'. app()->getLocale()};
        // } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
        //     return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->forestState->{'title_'. app()->getLocale()};
        // } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[2]){
        //     return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->{'title_'. app()->getLocale()}  ;
        // } else{
        //     return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->{'title_'. app()->getLocale()};
        // }
        
        
        
        if($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[6]){
            return $userInfo = @$locWiseAuthUserInfo->forestBeat->{'title_'. app()->getLocale()};
        } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[5]){
            return $userInfo = @$locWiseAuthUserInfo->forestRange->{'title_'. app()->getLocale()};
        } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[4]){
            return $userInfo = $locWiseAuthUserInfo->forestDivision->{'title_'. app()->getLocale()};
        } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[3]){
            return $userInfo = $locWiseAuthUserInfo->forestState->{'title_'. app()->getLocale()};
        } elseif($locWiseAuthUserInfo->userType->default_role == Admin::DEFAULT_ROLE_LIST[2]){
            return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->{'title_'. app()->getLocale()}  ;
        } else{
            return $userInfo = $locWiseAuthUserInfo->userType->{'title_'. app()->getLocale()} ." - ". $locWiseAuthUserInfo->{'title_'. app()->getLocale()};
        }
    
    
    
    }
}

if (!function_exists('authCheck')) {
    function authCheck()
    {
        if(Auth::guard('admin')->check()){
            return Auth::guard('admin')->user()->name;
        }
        elseif(Auth::guard('web')->check()){
            return Auth::guard('web')->user()->name;
        }
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if(Auth::guard('admin')->check()){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('isUser')) {
    function isUser()
    {
        if(Auth::guard('web')->check()){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('getCurrency')) {
    function getCurrency($number)
    {
        $get_locale_currency =  config('services.currency');
        $get_locale =  config('services.locale');
        $localeCurrency =  $get_locale_currency[app()->getLocale()] ?? 'USD';
        $locale =  $get_locale[app()->getLocale()] ?? 'en-US';

        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $bn =  $formatter->formatCurrency($number, $localeCurrency); // should be return if we get locale wise currency 

        $en =  'BDT ' . number_format($number, 2);

        return $result =  (app()->getLocale() == 'en') ? $en : $bn;
    }
}


if (!function_exists('getEnLang')) {
    function getEnLang()
    {
        $langEnDatas = Cache::rememberForever('langEnDatas', function () {
            $query = ModelsLang::get()->groupBy('page');
            return $level1datas = json_decode(json_encode($query), True);
        });

        $data = [];
        $output = [];
        foreach ($langEnDatas as $index1 => $level1data) {
            foreach ($level1data as $index2 => $value) {
            $key = $value['key'];
            $lang_1 = $value['lang_1'];

            $output[$key] = $lang_1;
            }
            $data[$index1] = $output;
        }
        return $data;
    }
}

if (!function_exists('getBnLang')) {
    function getBnLang()
    {
        $langBnDatas = Cache::rememberForever('langBnDatas', function () {
            $query = ModelsLang::get()->groupBy('page');
            return $level1datas = json_decode(json_encode($query), True);
        });

        $data = [];
        $output = [];
        foreach ($langBnDatas as $index1 => $level1data) {
            foreach ($level1data as $index2 => $value) {
            $key = $value['key'];
            $lang_2 = $value['lang_2'];

            $output[$key] = $lang_2;
            }
            $data[$index1] = $output;
        }
        return $data;
    }
}
