<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress as BaseBillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData as BaseBillingAddressData;

/**
 * @ORM\Table(name="billing_addresses")
 * @ORM\Entity
 */
class BillingAddress extends BaseBillingAddress
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isCompanyWithMultipleUsers;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddressData $billingAddressData
     */
    public function __construct(BaseBillingAddressData $billingAddressData)
    {
        $this->isCompanyWithMultipleUsers = $billingAddressData->isCompanyWithMultipleUsers;
        parent::__construct($billingAddressData);
    }

    /**
     * @return bool
     */
    public function getisCompanyWithMultipleUsers()
    {
        return $this->isCompanyWithMultipleUsers;
    }
}
