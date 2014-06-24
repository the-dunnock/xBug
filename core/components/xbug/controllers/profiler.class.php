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
 * Loads the home page.
 *
 * @package xbug
 * @subpackage controllers
 */
class xBugProfilerManagerController extends xBugManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('xbug.profiler'); }
    public function loadCustomCssJs() {
        $this->addLastJavascript($this->xbug->config['jsUrl'].'mgr/widgets/profiler.panel.js');
        $this->addLastJavascript($this->xbug->config['jsUrl'].'mgr/sections/profiler.js');
    }
    public function getTemplateFile() { return $this->xbug->config['templatesPath'].'profiler.tpl'; }
}