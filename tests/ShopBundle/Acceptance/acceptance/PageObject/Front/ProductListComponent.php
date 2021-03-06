<?php

namespace Tests\ShopBundle\Acceptance\acceptance\PageObject\Front;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\AbstractPage;

class ProductListComponent extends AbstractPage
{
    /**
     * @param string $productName
     * @param int $quantity
     * @param \Facebook\WebDriver\WebDriverElement $context
     */
    public function addProductToCartByName($productName, $quantity, WebDriverElement $context)
    {
        $productItemElement = $this->findProductListItemByName($productName, $context);

        $quantityElement = $productItemElement->findElement(WebDriverBy::name('add_product_form[quantity]'));
        $addButtonElement = $productItemElement->findElement(WebDriverBy::name('add_product_form[add]'));

        $this->uncoverAddToCartForm($productItemElement);
        $this->tester->fillFieldByElement($quantityElement, $quantity);
        $this->tester->clickByElement($addButtonElement);
        $this->tester->waitForAjax();
        $this->tester->wait(1); // animation of popup window
    }

    /**
     * By design, "add to cart" form is not visible on product list.
     * The product list item must be "hovered" to make the form visible.
     * Uncovering of the form is done via animation, therefore codeception must wait for it.
     *
     * @param \Facebook\WebDriver\WebDriverElement $productItemElement
     */
    private function uncoverAddToCartForm(WebDriverElement $productItemElement)
    {
        $this->tester->moveMouseOverByCss('#' . $productItemElement->getAttribute('id'));
        $this->tester->wait(2);
    }

    /**
     * @param string $productName
     * @param \Facebook\WebDriver\WebDriverElement $context
     * @return \Facebook\WebDriver\WebDriverElement
     */
    private function findProductListItemByName($productName, WebDriverElement $context)
    {
        $productItems = $context->findElements(WebDriverBy::cssSelector('.js-list-products-item'));

        foreach ($productItems as $item) {
            try {
                $nameElement = $item->findElement(WebDriverBy::cssSelector('.js-list-products-item-title'));

                if ($nameElement->getText() === $productName) {
                    return $item;
                }
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $ex) {
                continue;
            }
        }

        $message = 'Unable to find product "' . $productName . '" in product list component.';
        throw new \Facebook\WebDriver\Exception\NoSuchElementException($message);
    }
}
