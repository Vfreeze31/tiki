<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

 
class Multilingual_MachineTranslation_Null implements Multilingual_MachineTranslation_Interface
{
	function getSupportedLanguages()
	{
		return array();
	}

	function translateText($text)
	{
		return $text;
	}
}
