<?php
/**
 * xBug
 *
 * Copyright 2010 by Shaun McCormick <shaun+xbug@modx.com>
 *
 * xBug is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xBug is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xBug; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package xbug
 */
/**
 * Resolve paths. These are useful to change if you want to debug and/or develop
 * in a directory outside of the MODx webroot. They are not required to set
 * for basic usage.
 *
 * @package xbug
 * @subpackage build
 */
function createSetting(&$modx,$key,$value) {
    $ct = $modx->getCount('modSystemSetting',array(
        'key' => 'xbug.'.$key,
    ));
    if (empty($ct)) {
        $setting = $modx->newObject('modSystemSetting');
        $setting->set('key','xbug.'.$key);
        $setting->set('value',$value);
        $setting->set('namespace','xbug');
        $setting->set('area','Paths');
        $setting->save();
    }
}
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            /* setup paths */
            createSetting($modx,'core_path',$modx->getOption('core_path').'components/xbug/');
            createSetting($modx,'assets_path',$modx->getOption('assets_path').'components/xbug/');

            /* setup urls */
            createSetting($modx,'assets_url',$modx->getOption('assets_url').'components/xbug/');
            createSetting($modx,'xbug_auth_key',md5(time()));
            $modx->addExtensionPackage('xbug', $modx->getOption('core_path').'components/xbug/model/', array(
                'serviceName' => 'xbug', 'serviceClass' => 'xBug'
            ));
        break;
    }
}
return true;