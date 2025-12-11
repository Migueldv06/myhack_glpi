<?php

define('PLUGIN_MYHACK_VERSION', '0.1.0');
// versão Minima do GLPI, inclusive.
define("PLUGIN_MYHACK_MIN_GLPI_VERSION", "10.0.0");
// versão Maximum GLPI, exclusive
define("PLUGIN_MYHACK_MAX_GLPI_VERSION", "10.0.99");

function plugin_version_myhack() {
    return [
        'name'           => 'MyHack',
        'version'        => '0.1.1',
        'author'         => 'Miguel Domiciano Vieira',
        'license'        => 'GPLv2+',
        'homepage'       => 'https://www.ambientelivre.com.br',
        'requirements'   => [
            'glpi' => [
                'min' => PLUGIN_MYHACK_MIN_GLPI_VERSION,
                'max' => PLUGIN_MYHACK_MAX_GLPI_VERSION,
            ],
        ]
    ];
}

function plugin_init_myhack() {
    global $PLUGIN_HOOKS;
    $PLUGIN_HOOKS['csrf_compliant']['myhack'] = true;

    $PLUGIN_HOOKS["menu_toadd"]['myhack'] = [
        'plugins' => PluginMyhackMenu::class,
        'config' => PluginMyhackMenuSettings::class];

}

function plugin_myhack_check_prerequisites() {
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        return false;
    }
    return true;
}
