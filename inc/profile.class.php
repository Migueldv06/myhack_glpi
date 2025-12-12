<?php

class PluginMyhackProfile extends Profile
{

    public static $rightname = "profile";

    public function getTabNameForItem(CommonGLPI $item, $withteamplate = 0)
    {
        if ($item->getType() == 'Profile') {
            return __('MyHack Config', 'myhack');
        }
        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withteamplate = 0)
    {
        if ($item->getType() == 'Profile') {
            $ID = $item->getID();
            $prof = new self();

            self::addDefaultProfilesInfos($ID, ['plugin_myhack_config' => 0]);
            $prof->showForm($ID);
        }
        return true;
    }

    public static function createFirstAccess($ID)
    {
        self::addDefaultProfilesInfos($ID, ['plugin_myhack_config' => ALLSTANDARDRIGHT], true);
    }

    public static function addDefaultProfilesInfos($profiles_id, $rights, $drop_existing = false)
    {
        $profileRight = new ProfileRight();
        $dbu = new DbUtils();

        foreach ($rights as $right => $value) {
            if ($dbu->countElementsInTable(
                'glpi_profilerights',
                ["profiles_id" => $profiles_id, "name" => $right]
            ) && $drop_existing) {
                $profileRight->deleteByCriteria(['profiles_id' => $profiles_id, 'name' => $right]);
            }

            if (!$dbu->countElementsInTable(
                'glpi_profilerights',
                ["profiles_id" => $profiles_id, "name" => $right]
            )) {
                $myright['profiles_id'] = $profiles_id;
                $myright['name'] = $right;
                $myright['right'] = $value;
                $profileRight->add($myright);
            }
        }
    }

    public function showForm($profiles_id, $openform = true, $closeform = true)
    {
        echo "<div class='firstbloc'>";
        echo "teste";

        if (($canedit = Session::haveRightsOr(self::$rightname, [CREATE, UPDATE])) && $openform) {
            $profile = new Profile();
            echo "<form method='post' action='" . $profile->getFormURL() . "'>";
        }

        $profile = new Profile();
        $profile->getFromDB($profiles_id);


        if ($profile->getField('interface') == 'central') {
            $rights = $this->getAllRights();
            $profile->displayRightsChoiceMatrix($rights, [
                'canedit' => $canedit,
                'default_class' => 'tab_bg_2',
                'title' => __("MyHack Configurations", 'myhack')
            ]);
        }

        if ($canedit && $closeform) {
            echo "<div class='center'>";
            echo Html::hidden('id', ['value' => $profiles_id]);
            echo Html::submit(_sx('button', 'Save'), ['name' => 'update', 'class' => 'btn btn-primary']);
            echo "</div>\n";
            Html::closeForm();
        }

        echo "</div>";
    }

    public static function getAllRights()
    {
        $rights = [[    
            'itemtype' => 'PluginMyhackConfig',
            'label' => __('Myhack access', 'myhack'),
            'field' => 'plugin_myhack_config',
            'rights' => [
                READ => __('Read'),
                UPDATE=> __('Update')
            ]
        ]];

        return $rights;
    }

    public static function initProfile()
    {
        global $DB;

        $profile = new self();
        $dbu = new DbUtils;
        foreach ($profile->getAllRights() as $data) {
            if ($dbu->countElementsInTable(
                "glpi_profilerights",
                ["name" => $data['field']]
            ) == 0) {
                ProfileRight::addProfileRights([$data['field']]);
            }
        }

        if (isset($_SESSION['glpiactiveprofile']['id'])) {
            $iterator = $DB->requests([
                'FROM' => 'glpi_profilerights',
                'WHERE' => [
                    'profile_id' => $_SESSION['glpiactiveprofile']['id'],
                    'name' => 'plugin_myhack_config'
                ]
            ]);

            foreach ($iterator as $prof) {
                $_SESSION['glpiactivateprofile'][$prof['name'] = $prof['rights']];
            }
        }
    }

    public static function removeRightsFromSession()
    {
        foreach (self::getAllRights(true) as $right) {
            if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
                unset($_SESSION['glpiactiveprofile'][$right['field']]);
            }
        }
    }
}
