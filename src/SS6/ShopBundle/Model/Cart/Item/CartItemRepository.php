<?php

namespace SS6\ShopBundle\Model\Cart\Item;

use Doctrine\ORM\EntityManager;
use SS6\ShopBundle\Model\Cart\Item\CartItem;
use SS6\ShopBundle\Model\Customer\CustomerIdentifier;

class CartItemRepository {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;
	
	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
	}
	
	/**
	 * @return \Doctrine\ORM\EntityRepository
	 */
	private function getCartItemRepository() {
		return $this->em->getRepository(CartItem::class);
	}
	
	/**
	 * @param \SS6\ShopBundle\Model\Customer\CustomerIdentifier $customerIdentifier
	 * @return array
	 */
	public function findAllByCustomerIdentifier(CustomerIdentifier $customerIdentifier) {
		$criteria = array();
		if ($customerIdentifier->getUser() !== null) {
			$criteria['user'] = $customerIdentifier->getUser()->getId();
		} else {
			$criteria['sessionId'] = $customerIdentifier->getSessionId();
		}
		
		return $this->getCartItemRepository()->findBy($criteria, array('id' => 'desc'));
	}
	
}