<?php

namespace SS6\ShopBundle\Model\Feed;

use SS6\ShopBundle\Component\Domain\Config\DomainConfig;

interface FeedDataSourceInterface {

	/**
	 * @param \SS6\ShopBundle\Component\Domain\Config\DomainConfig $domainConfig
	 * @return \Iterator
	 */
	public function getIterator(DomainConfig $domainConfig);
}
