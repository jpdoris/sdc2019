<?php

namespace App;

class DataParser {

	public static function parseData()
	{
		$input = array_key_exists( 'action', $_POST) ? $_POST['action'] : null;

		if ($input) {
			switch($input) {
				case "listTopics":
					self::listTopics();
					break;

				case "listTypes":
					self::listTypes();
					break;

				case "listLevels":
					self::listLevels();
					break;

				case "applyFilters";
					$filters = array_key_exists( 'filters', $_POST) ? $_POST['filters'] : null;
					self::applyFilters($filters);
					break;

				case "getSessionByTitle":
					$filter = array_key_exists( 'title', $_POST) ? $_POST['title'] : null;
					self::getSessionByTitle($filter);
					break;

				case "sessionTextSearch":
					$criteria = array_key_exists( 'search', $_POST) ? $_POST['search'] : null;
					self::sessionTextSearch($criteria);
					break;
			}
		}

		return null;
	}

	public static function readCacheFile()
	{
		$cachefile = __DIR__ . '/presentationcache';
		$contents = file_get_contents( $cachefile );

		return $contents;
	}

	public static function listTopics()
	{
		$json = json_decode(self::readCacheFile());
		$data = [];
		foreach($json as $session => $array) {
			if ($array->CustomPresField2) {
				if (!in_array($array->CustomPresField2, $data)) {
					$data[$array->ROW] = $array->CustomPresField2;
				}
			}
		}

		echo json_encode($data);
	}

	public static function listTypes()
	{
		$json = json_decode(self::readCacheFile());
		$data = [];
		foreach($json as $session => $array) {
			if ($array->CustomPresField15) {
				if (!in_array($array->CustomPresField15, $data)) {
					$data[$array->ROW] = $array->CustomPresField15;
				}
			}
		}

		echo json_encode($data);
	}

	public static function listLevels()
	{
		$json = json_decode(self::readCacheFile());
		$data = [];
		foreach($json as $session => $array) {
			if ($array->PresentationTargetAudience) {
				if (!in_array($array->PresentationTargetAudience, $data)) {
					$data[$array->ROW] = $array->PresentationTargetAudience;
				}
			}
		}

		echo json_encode($data);
	}

	public static function applyFilters($filters)
	{
		$json   = json_decode(self::readCacheFile());
		$filter = json_decode($filters, false);
		$topics = $filter->topics;
		$search = $filter->search;
		$day 	= $filter->day;
		$type 	= $filter->type;
		$level 	= $filter->level;
		$data 	= [];

		foreach ($json as $session => $array) {
            if ($array->CustomPresField2) {
                $data[$array->ROW]['Topic'] = $array->CustomPresField2;
                $data[$array->ROW]['PresentationTimeStart'] = $array->PresentationTimeStart;
                $data[$array->ROW]['SessionName'] = $array->SessionName;
//                $data[$array->ROW]['SessionName'] = $array->CustomPresField7;
                $data[$array->ROW]['Description'] = $array->CustomPresField10;
                $data[$array->ROW]['PresentationDate'] = $array->PresentationDate;
                $data[$array->ROW]['Type'] = $array->CustomPresField15;
                $data[$array->ROW]['Level'] = $array->PresentationTargetAudience;
            }
        }
			
        if (sizeof((array)$filter)) {
            foreach ($data as $row => $values) {

                if (sizeof((array)$topics)) {
                    if (!in_array($values['Topic'], $topics)) {
                        unset($data[$row]);
                    }
                }

                if ($search) {
                    if (!in_array($values['Topic'], $search) &&
                        !in_array($values['Description'], $search) &&
                        !in_array($values['SessionName'], $search)) {
                        unset($data[$row]);
                    }
                }

                if (sizeof((array)$day)) {
                    if (!in_array($values['PresentationDate'], $day)) {
                        unset($data[$row]);
                    }
                }

                if (sizeof((array)$type)) {
                    if (!in_array($values['Type'], $type)) {
                        unset($data[$row]);
                    }
                }

                if (sizeof((array)$level)) {
                    if (!in_array($values['Level'], $level)) {
                        unset($data[$row]);
                    }
                }
                
            }
        }

		echo json_encode($data);
	}

	public static function getSessionByTitle($title)
	{
		$json = json_decode(self::readCacheFile());
		$filter = json_decode($title);
		$data = [];
		foreach($json as $session => $array) {
			if ($array->SessionName == $filter) {
				$data = $array;
			}
		}

		echo json_encode($data);
	}

	public static function sessionTextSearch($criteria)
	{
		$json = json_decode(self::readCacheFile());
		$fields = [];
		$data = [];
		foreach($json as $session => $array) {
			$fields[$array->ROW] = [
				$array->SessionName,
				$array->CustomPresField7,
				$array->CustomPresField10,
			];

			if (in_array($criteria, $fields[$array->ROW])) {
				$data[$array->ROW] = $fields[$array->ROW];
			}
		}

		echo json_encode($data);
	}
}
