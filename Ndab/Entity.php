<?php

/**
 * This file is part of the Ndab
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Ndab;

use Nette,
	Nette\Database\Table;



/**
 * Ndab base model entity
 *
 * @author  Jan Skrasek
 */
class Entity extends Table\ActiveRow
{
	/** @var array */
	protected static $subRelations = array();



	public function & __get($key)
	{
		$method = "get$key";
		$method[3] = $method[3] & "\xDF";

		if (!$this->__isset($key) && method_exists($this, $method)) {
			$return = $this->$method();
			return $return;
		}

		return parent::__get($key);
	}



	/**
	 * Returns array of subItems fetched form related() call
	 * @param  string  "relatedTable:subItem"
	 * @param  Nette\Callback  callback for additional related call definition
	 * @return array
	 */
	protected function getSubRelation($selector, Nette\Callback $relatedCallback = NULL)
	{
		list($relatedSelector, $subItemSelector) = explode(':', $selector);

		$related = $this->related($relatedSelector);
		if ($relatedCallback) {
			$relatedCallback->invokeArgs(array($related));
		}

		$subItems = array();
		foreach ($related as $subItem) {
			$subItems[] = $subItem->$subItemSelector;
		}

		return $subItems;
	}

}