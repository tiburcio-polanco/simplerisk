<?php

/********************************************************************
 * COPYRIGHT NOTICE:                                                *
 * This Source Code Form is copyrighted 2019 to SimpleRisk, LLC and *
 * cannot be used or duplicated without express written permission. *
 ********************************************************************/

global $ucf_updates;
$ucf_updates = array(
);

/****************************************
 * FUNCTION: UPGRADE UCF EXTRA DATABASE *
 ****************************************/
function upgrade_ucf_extra_database()
{
    global $ucf_updates;

    $version_name = 'ucf_extra_version';

    // Get the current database version
    $db_version = get_setting($version_name);

    // If the database setting does not exist
    if(!$db_version)
    {
        // Set the initial version to 0
        $db_version = 0;
        update_or_insert_setting($version_name, $db_version);
    }

    // If there is a function to upgrade to the next version
    if (array_key_exists($db_version, $ucf_updates))
    {
        // Get the function to upgrade to the next version
        $function = $ucf_updates[$db_version];

        // If the function exists
        if (function_exists($function))
        {
            // Call the function
            call_user_func($function);

            // Set the next database version
            $db_version = $db_version + 1;

            // Update the database version
            update_or_insert_setting($version_name, $db_version);

            // Call the upgrade function again
            upgrade_ucf_extra_database();
        }
    }
}

?>
