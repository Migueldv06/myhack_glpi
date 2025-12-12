<?php

include ('../../../inc/includes.php');  

Session::checkLoginUser();

Session::checkRight("plugin_myhack_config", UPDATE);

Html::header(__('MyHack Settings', 'myhack'), $_SERVER['PHP_SELF'], "config","PluginMyhackMenu");

if (isset($_POST['salvar_config'])) {
    Config::setConfigurationValues('plugin:myhack', ['api_url' => $_POST['api_url']]);
}

$config = Config::getConfigurationValues('plugin:myhack');
$url_atual = isset($config['api_url']) ? $config['api_url'] : '';

echo "<div class='center'>";
echo "<h1>Configurar API</h1>";
    echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";

        echo "<input type='hidden' name='_glpi_csrf_token' value='".Session::getNewCSRFToken()."'>";

        echo "<label>Informe a URL da API:</label><br>";
        echo "<input type='text' name='api_url' value='$url_atual' style='width: 400px; padding: 5px;' placeholder='https://...'><br><br>";
        
        echo "<input type='submit' name='salvar_config' value='Salvar Configuração' class='btn btn-primary'>";
    echo "</form>";
echo "</div>";

Html::footer();
