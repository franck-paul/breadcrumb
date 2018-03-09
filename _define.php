<?php
/**
 * @brief breadcrumb, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "Breadcrumb",              // Name
    "Breadcrumb for Dotclear", // Description
    "Franck Paul",             // Author
    '0.6.1',                   // Version
    array(
        'permissions' => 'usage,contentadmin', // Permissions
        'type'        => 'plugin'             // Type
    )
);
