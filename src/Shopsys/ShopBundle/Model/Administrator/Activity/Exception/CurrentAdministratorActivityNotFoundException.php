<?php

namespace Shopsys\ShopBundle\Model\Administrator\Activity\Exception;

use Exception;
use Shopsys\ShopBundle\Model\Administrator\Activity\Exception\AdministratorActivityException;

class CurrentAdministratorActivityNotFoundException extends Exception implements AdministratorActivityException
{
    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
