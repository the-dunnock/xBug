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
 * Adds modActions and modMenus into package
 *
 * @package xbug
 * @subpackage build
 */
$action= $modx->newObject('modAction');
$action->fromArray(array(
    'id' => 1,
    'namespace' => 'xbug',
    'parent' => 0,
    'controller' => 'index',
    'haslayout' => 1,
    'lang_topics' => 'xbug:default',
    'assets' => '',
),'',true,true);

/* load action into menu */
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'xbug.xbug',
    'parent' => 'components',
    'description' => 'xbug.menu_desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => 'return false;',
),'',true,true);
$menu->addOne($action);
$vehicle = $builder->createVehicle($menu, array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
        )
    )
));
$builder->putVehicle($vehicle);
unset($menu, $vehicle);


$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'xbug.profiler',
    'description' => 'xbug.profiler.menu_desc',
    'menuindex' => 1,
    'params' => '&action=profiler',
    'handler' => '',
    'parent' => 'xbug.xbug'
),'',true,true);
$menu->addOne($action);
$vehicle = $builder->createVehicle($menu, array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
        )
    )
));
$builder->putVehicle($vehicle);
unset($menu, $vehicle);


$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'xbug.xbug_query',
    'description' => 'xbug.xbug_query_desc',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
    'parent' => 'xbug.xbug'
),'',true,true);
$menu->addOne($action);
$vehicle = $builder->createVehicle($menu, array (
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::UNIQUE_KEY => 'text',
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Action' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => array ('namespace','controller'),
        )
    )
));
$builder->putVehicle($vehicle);
unset($menu, $vehicle);

return $menu;