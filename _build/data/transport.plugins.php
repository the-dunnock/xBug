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
 * Add snippets to build
 * 
 * @package xbug
 * @subpackage build
 */

$plugins[0]= $modx->newObject('modPlugin');
$plugins[0]->fromArray(array(
    'id' => 0,
    'name' => 'xBugEvents',
    'description' => 'Used by xBugger page profiler. Do not enable this plugin.',
    'plugincode' => getSnippetContent($sources['plugins'].'xBugEvents.plugin.php'),
    'category' => 0
),'',true,true);

return $plugins;