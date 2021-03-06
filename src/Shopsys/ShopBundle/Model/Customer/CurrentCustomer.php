<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\CurrentCustomer as BaseCurrentCustomer;

class CurrentCustomer extends BaseCurrentCustomer
{
    /**
     * @return int|string
     */
    public function getDiscountCoeficient()
    {
        $user = $this->findCurrentUser();
        if ($user === null) {
            return 1;
        } else {
            return json_encode((100 - $user->getDiscount()) / 100);
        }
    }
}
