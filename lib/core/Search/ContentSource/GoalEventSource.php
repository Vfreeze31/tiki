<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_ContentSource_GoalEventSource implements Search_ContentSource_Interface
{
	private $table;

	function __construct()
	{
		$this->table = TikiDb::get()->table('tiki_goal_events');
	}

	function getDocuments()
	{
		return $this->table->fetchColumn('eventId', []);
	}

	function getDocument($objectId, Search_Type_Factory_Interface $typeFactory)
	{
		global $prefs;

		$event = $this->table->fetchRow(['eventType', 'eventDate', 'user', 'groups', 'targetType', 'targetObject'], [
			'eventId' => $objectId,
		]);

		if ($event) {
			$target = null;
			if ($event['targetType'] && $event['targetObject']) {
				$target = "{$event['targetType']}:{$event['targetObject']}";
			}
			$goal_groups = array_values(json_decode($event['groups'], true));
			return [
				'modification_date' => $typeFactory->timestamp($event['eventDate']),
				'event_type' => $typeFactory->identifier($event['eventType']),
				'user' => $typeFactory->identifier($event['user']),
				'goal_groups' => $typeFactory->multivalue($goal_groups),
				'goal_groups_array' => $typeFactory->plaintext(json_encode($goal_groups)),
				'target' => $typeFactory->identifier($target),
			];
		} else {
			return false;
		}
	}

	function getProvidedFields()
	{
		return ['event_type', 'modification_date', 'user', 'goal_groups', 'goal_groups_array', 'target'];
	}

	function getGlobalFields()
	{
		return array(
		);
	}
}

