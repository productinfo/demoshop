<?php

namespace SS6\ShopBundle\DataFixtures\Performance;

use Doctrine\ORM\EntityManager;
use Faker\Generator as Faker;
use SS6\ShopBundle\Component\DataFixture\PersistentReferenceService;
use SS6\ShopBundle\Component\Doctrine\SqlLoggerFacade;
use SS6\ShopBundle\Component\Domain\Domain;
use SS6\ShopBundle\Model\Customer\BillingAddressData;
use SS6\ShopBundle\Model\Customer\CustomerData;
use SS6\ShopBundle\Model\Customer\CustomerEditFacade;
use SS6\ShopBundle\Model\Customer\DeliveryAddressData;
use SS6\ShopBundle\Model\Customer\UserDataFactory;

class UserDataFixture {

	const USERS_ON_EACH_DOMAIN = 100;
	const FIRST_PERFORMANCE_USER = 'first_performance_user';

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \SS6\ShopBundle\Component\Domain\Domain
	 */
	private $domain;

	/**
	 * @var \SS6\ShopBundle\Component\Doctrine\SqlLoggerFacade
	 */
	private $sqlLoggerFacade;

	/**
	 * @var \SS6\ShopBundle\Model\Customer\CustomerEditFacade
	 */
	private $customerEditFacade;

	/**
	 * @var \SS6\ShopBundle\Model\Customer\UserDataFactory
	 */
	private $userDataFactory;

	/**
	 * @var \Faker\Generator
	 */
	private $faker;

	/**
	 * @var \SS6\ShopBundle\Component\DataFixture\PersistentReferenceService
	 */
	private $persistentReferenceService;

	public function __construct(
		EntityManager $em,
		Domain $domain,
		SqlLoggerFacade $sqlLoggerFacade,
		CustomerEditFacade $customerEditFacade,
		UserDataFactory $userDataFactory,
		Faker $faker,
		PersistentReferenceService $persistentReferenceService
	) {
		$this->em = $em;
		$this->domain = $domain;
		$this->sqlLoggerFacade = $sqlLoggerFacade;
		$this->customerEditFacade = $customerEditFacade;
		$this->userDataFactory = $userDataFactory;
		$this->faker = $faker;
		$this->persistentReferenceService = $persistentReferenceService;
	}

	public function load() {
		// Sql logging during mass data import makes memory leak
		$this->sqlLoggerFacade->temporarilyDisableLogging();

		$isFirstUser = true;

		foreach ($this->domain->getAll() as $domainConfig) {
			for ($i = 0; $i <  self::USERS_ON_EACH_DOMAIN; $i++) {
				$user = $this->createCustomerOnDomain($domainConfig->getId(), $i);

				if ($isFirstUser) {
					$this->persistentReferenceService->persistReference(self::FIRST_PERFORMANCE_USER, $user);
					$isFirstUser = false;
				}

				$this->em->clear();
			}
		}

		$this->sqlLoggerFacade->reenableLogging();
	}

	/**
	 * @param int $domainId
	 * @param int $userNumber
	 * @return \SS6\ShopBundle\Model\Customer\User
	 */
	private function createCustomerOnDomain($domainId, $userNumber) {
		$customerData = $this->getRandomCustomerDataByDomainId($domainId, $userNumber);

		return $this->customerEditFacade->create($customerData);
	}

	/**
	 * @param int $domainId
	 * @param int $userNumber
	 * @return \SS6\ShopBundle\Model\Customer\CustomerData
	 */
	private function getRandomCustomerDataByDomainId($domainId, $userNumber) {
		$customerData = new CustomerData();

		$userData = $this->userDataFactory->createDefault($domainId);
		$userData->firstName = $this->faker->firstName;
		$userData->lastName = $this->faker->lastName;
		$userData->email = $userNumber . '.' . $this->faker->safeEmail;
		$userData->password = $this->faker->password;
		$userData->domainId = $domainId;
		$customerData->userData = $userData;

		$billingAddressData = new BillingAddressData();
		$billingAddressData->companyCustomer = $this->faker->boolean();
		if ($billingAddressData->companyCustomer === true) {
			$billingAddressData->companyName = $this->faker->company;
			$billingAddressData->companyNumber = $this->faker->randomNumber(6);
			$billingAddressData->companyTaxNumber = $this->faker->randomNumber(6);
		}
		$billingAddressData->street = $this->faker->streetAddress;
		$billingAddressData->city = $this->faker->city;
		$billingAddressData->postcode = $this->faker->postcode;
		$billingAddressData->telephone = $this->faker->phoneNumber;
		$customerData->billingAddressData = $billingAddressData;

		$deliveryAddressData = new DeliveryAddressData();
		$deliveryAddressData->addressFilled = true;
		$deliveryAddressData->city = $this->faker->city;
		$deliveryAddressData->companyName = $this->faker->company;
		$deliveryAddressData->contactPerson = $this->faker->name;
		$deliveryAddressData->postcode = $this->faker->postcode;
		$deliveryAddressData->street = $this->faker->streetAddress;
		$deliveryAddressData->telephone = $this->faker->phoneNumber;
		$customerData->deliveryAddressData = $deliveryAddressData;

		return $customerData;
	}

}
