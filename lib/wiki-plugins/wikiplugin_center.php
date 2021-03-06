<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_center_info()
{
	return array(
		'name' => tra('Center'),
		'documentation' => 'PluginCenter',
		'description' => tra('Center text'),
		'prefs' => array('wikiplugin_center'),
		'body' => tra('text'),
		'iconname' => 'align-center',
		'filter' => 'wikicontent',
		'tags' => array( 'basic' ),
		'introduced' => 1,
		'format' => 'html',
		'params' => array(
		),
	);
}

/**
 * @param $data string data to center
 * @param $params array ignored
 *
 * @return string HTML formatted centered text.
 */
function wikiplugin_center($data, $params)
{
	return '<div style="text-align:center">' . TikiLib::lib('parser')->parse_data_plugin($data). '</div>';
}
