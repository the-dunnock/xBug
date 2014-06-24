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
class xBugIndexManagerController extends xBugManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('xbug'); }
    public function loadCustomCssJs() {
        $ace = $this->modx->getObject('transport.modTransportPackage', array('package_name' => 'Ace', 'disabled' => 0));
        if ($ace) {
            $ace = $this->modx->getService('ace','Ace',$this->modx->getOption('ace.core_path',null,$this->modx->getOption('core_path').'components/ace/').'model/ace/');
            $ace->initialize();
            $script = "
            setTimeout(function(){
                var textArea = Ext.getCmp('xbug-editor-text');
                var textEditor = MODx.load({
                    xtype: 'modx-texteditor',
                    enableKeyEvents: true,
                    anchor: textArea.anchor,
                    width: 'auto',
                    height: textArea.container.getHeight(),
                    name: textArea.name,
                    flext : 1,
                    value: textArea.getValue(),
                    mimeType: 'application/x-php',
                    id : 'xbug-editor-ace'
                });
        
                textArea.el.dom.removeAttribute('name');
                textArea.el.setStyle('display', 'none');
                textEditor.render(textArea.el.dom.parentNode);
                textEditor.on('keydown', function(e){textArea.fireEvent('keydown', e);});
            });";
            
            $this->addHtml('<script>Ext.onReady(function() {' . $script . '});</script>');
        }
        $this->addLastJavascript($this->xbug->config['jsUrl'].'mgr/widgets/index.panel.js');
        $this->addLastJavascript($this->xbug->config['jsUrl'].'mgr/sections/index.js');
    }
    public function getTemplateFile() { return $this->xbug->config['templatesPath'].'index.tpl'; }
}