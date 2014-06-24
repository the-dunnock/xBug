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
 * Properties for the xBug snippet.
 *
 * @package xbug
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'tpl',
        'desc' => 'prop_xbug.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'Item',
        'lexicon' => 'xbug:properties',
    ),
    array(
        'name' => 'sortBy',
        'desc' => 'prop_xbug.sortby_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'name',
        'lexicon' => 'xbug:properties',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'prop_xbug.sortdir_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
        'lexicon' => 'xbug:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'prop_xbug.limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 5,
        'lexicon' => 'xbug:properties',
    ),
    array(
        'name' => 'outputSeparator',
        'desc' => 'prop_xbug.outputseparator_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'xbug:properties',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'prop_xbug.toplaceholder_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => true,
        'lexicon' => 'xbug:properties',
    ),
/*
    array(
        'name' => '',
        'desc' => 'prop_xbug.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'xbug:properties',
    ),
    */
);

return $properties;