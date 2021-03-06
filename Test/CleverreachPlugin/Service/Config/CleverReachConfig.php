<?php

namespace Test\CleverreachPlugin\Service\Config;

class CleverReachConfig
{
    const MENU_ID = 'Test_CleverreachPlugin::cleverreach_landingpage';

    const CLIENT_ID = 'CFkMVkzRPM';
    const CLIENT_SECRET = 'SNfWYY6lkdgxevBzCuq752MqOHKozzar';

    const TOKEN_URL = "https://rest.cleverreach.com/oauth/token.php";
    const AUTHORIZE_URL = 'https://rest.cleverreach.com/oauth/authorize.php';

    const BASE_GROUP_URL = 'https://rest.cleverreach.com/v3/groups.json';
    const DEBUG_URL = 'https://rest.cleverreach.com/v3/debug/whoami.json';

    const NUMBER_OF_RECEIVERS_IN_GROUP = 50;

    const ACCESS_TOKEN_NAME = 'accessToken';
    const GROUP_INFO_NAME = 'groupInfo';
    const CLIENT_INFO_NAME = 'clientInfo';
}
