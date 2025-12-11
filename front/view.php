<?php

include ('../../../inc/includes.php');

Session::checkLoginUser();

Html::header(__('MyHack Settings', 'myhack'), $_SERVER['PHP_SELF'], "plugins","PluginMyhackMenu");

$config = Config::getConfigurationValues('plugin:myhack');
$url = isset($config['api_url']) ? $config['api_url'] : '';

echo "<div class='center'>";
    if(empty($url)){
        echo "<h3>Sem URL configurada</h3>";
    }
    else {

        $url = htmlspecialchars_decode($url);

        echo "<h3>Resultado da API</h3>";
        echo "<p>URL: " . $url . "</p>";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        if ($error){
            echo "erro na requisição da api";
        } else {
            echo htmlspecialchars($response);
        }
    }



echo "</div>";

Html::footer();
