<?php

namespace Tests\ShopBundle\Functional\Model\Unit;

use Shopsys\FrameworkBundle\Model\Product\ProductFacade;
use Shopsys\FrameworkBundle\Model\Product\Unit\UnitData;
use Shopsys\FrameworkBundle\Model\Product\Unit\UnitFacade;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\UnitDataFixture;
use Shopsys\ShopBundle\Model\Product\ProductDataFactory;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class UnitFacadeTest extends TransactionFunctionalTestCase
{
    public function testDeleteByIdAndReplace()
    {
        $em = $this->getEntityManager();
        $unitFacade = $this->getContainer()->get(UnitFacade::class);
        /* @var $unitFacade \Shopsys\FrameworkBundle\Model\Product\Unit\UnitFacade */
        $productDataFactory = $this->getContainer()->get(ProductDataFactory::class);
        /* @var $productDataFactory \Shopsys\ShopBundle\Model\Product\ProductDataFactory */
        $productFacade = $this->getContainer()->get(ProductFacade::class);
        /* @var $productFacade \Shopsys\FrameworkBundle\Model\Product\ProductFacade */

        $unitData = new UnitData();
        $unitData->name = ['cs' => 'name'];
        $unitToDelete = $unitFacade->create($unitData);
        $unitToReplaceWith = $this->getReference(UnitDataFixture::UNIT_PIECES);
        /* @var $newUnit \Shopsys\FrameworkBundle\Model\Product\Unit\Unit */
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        /* @var $product \Shopsys\ShopBundle\Model\Product\Product */
        $productData = $productDataFactory->createFromProduct($product);
        /* @var $productData \Shopsys\ShopBundle\Model\Product\ProductData */

        $productData->unit = $unitToDelete;
        $productFacade->edit($product->getId(), $productData);

        $unitFacade->deleteById($unitToDelete->getId(), $unitToReplaceWith->getId());

        $em->refresh($product);

        $this->assertEquals($unitToReplaceWith, $product->getUnit());
    }
}
