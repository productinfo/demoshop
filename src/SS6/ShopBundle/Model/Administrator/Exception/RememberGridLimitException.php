<?php

namespace SS6\ShopBundle\Model\Administrator\Exception;

use Exception;

class RememberGridLimitException extends Exception implements AdministratorException {

	/**
	 * @var string
	 */
	private $gridId;

	/**
	 * @param string $gridId
	 * @param Exception $previous
	 */
	public function __construct($gridId, $previous = null) {
		$this->gridId = $gridId;
		parent::__construct('Grid \'' . $this->gridId . ' \' do not has allowed pager', 0, $previous);
	}

	/**
	 * @return string
	 */
	public function getGridId() {
		return $this->gridId;
	}

}