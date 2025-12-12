<?php
//include ('../../../inc/includes.php');

Session::checkLoginUser();


class PluginMyhackMenu extends CommonGLPI {
    static function getMenuName(){
        return __('Myhack','myhack');
    }

    static function getMenuContent(){

        if(!Session::haveRight("config", READ)){
            return;
        }

        $menu = [];

        $menu['title'] = self::getMenuName();
        $menu['page'] = '/plugins/myhack/front/view.php';
        $menu['icon'] = 'ti ti-eye';

        $menu['options']['view']['title'] = __('View','myhack');
        $menu['options']['view']['page'] = '/plugins/myhack/front/view.php';
        $menu['options']['view']['icon'] = 'ti ti-eye';

        return $menu;
    }
}

class PluginMyhackMenuSettings extends CommonGLPI {
    static function getMenuName(){
        return __('Myhack Settings','myhack');
    }

    static function getMenuContent(){

        if(!Session::haveRight("config", UPDATE)){
            return;
        }

        $menu = [];

        $menu['title'] = self::getMenuName();
        $menu['page'] = '/plugins/myhack/front/settings.php';
        $menu['icon'] = 'ti ti-rss';

        $menu['options']['settings']['title'] = __('Settings','myhack');
        $menu['options']['settings']['page'] = '/plugins/myhack/front/settings.php';
        $menu['options']['settings']['icon'] = 'ti ti-rss';

        return $menu;
    }
}
