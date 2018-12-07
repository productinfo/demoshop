<?php

namespace Tests\ShopBundle\Acceptance\acceptance\PageObject\Front;

use Facebook\WebDriver\WebDriverBy;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\AbstractPage;
use Tests\ShopBundle\Test\Codeception\AcceptanceTester;
use Tests\ShopBundle\Test\Codeception\Module\StrictWebDriver;

class ProductFilterPage extends AbstractPage
{
    // Product filter waits for more requests before evaluation
    const PRE_EVALUATION_WAIT = 2;

    /**
     * @param \Tests\ShopBundle\Test\Codeception\Module\StrictWebDriver $strictWebDriver
     * @param \Tests\ShopBundle\Test\Codeception\AcceptanceTester $tester
     */
    public function __construct(StrictWebDriver $strictWebDriver, AcceptanceTester $tester)
    {
        parent::__construct($strictWebDriver, $tester);
    }

    /**
     * @param string $price
     */
    public function setMinimalPrice($price)
    {
        /**
         * The input for minimal price is hidden by design so Codeception is not able to fill it directly.
         * There is a jQueryUI price slider in the filter form but making Codeception to handle it correctly would be overkill.
         */
        $this->tester->executeJS(sprintf('$("#product_filter_form_minimalPrice").val(%.2f).change()', $price));
        $this->waitForFilter();
    }

    /**
     * @param string $price
     */
    public function setMaximalPrice($price)
    {
        /**
         * The input for maximal price is hidden by design so Codeception is not able to fill it directly.
         * There is a jQueryUI price slider in the filter form but making Codeception to handle it correctly would be overkill.
         */
        $this->tester->executeJS(sprintf('$("#product_filter_form_maximalPrice").val(%.2f).change()', $price));
        $this->waitForFilter();
    }

    /**
     * @param string $label
     */
    public function filterByBrand($label)
    {
        $this->tester->clickByCss('.js-brand-filter-label-' . $label);
        $this->waitForFilter();
    }

    /**
     * @param string $parameterLabel
     * @param string $valueLabel
     */
    public function filterByParameter($parameterLabel, $valueLabel)
    {
        $parameterElement = $this->findParameterElementByLabel($parameterLabel);
        $labelElement = $this->getLabelElementByParameterValueText($parameterElement, $valueLabel);
        $labelElement->click();
        $this->waitForFilter();
    }

    private function waitForFilter()
    {
        $this->tester->wait(self::PRE_EVALUATION_WAIT);
        $this->tester->waitForAjax();
    }

    /**
     * @param string $parameterLabel
     * @return \Facebook\WebDriver\WebDriverElement
     */
    private function findParameterElementByLabel($parameterLabel)
    {
        $parameterItems = $this->webDriver->findElements(
            WebDriverBy::cssSelector('#product_filter_form_parameters .js-product-filter-parameter')
        );

        foreach ($parameterItems as $item) {
            try {
                $itemLabel = $item->findElement(WebDriverBy::cssSelector('.js-product-filter-parameter-label'));

                if (stripos($itemLabel->getText(), $parameterLabel) !== false) {
                    return $item;
                }
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $ex) {
                continue;
            }
        }

        $message = 'Unable to find parameter with label "' . $parameterLabel . '" in product filter.';
        throw new \Facebook\WebDriver\Exception\NoSuchElementException($message);
    }

    /**
     * @param \Facebook\WebDriver\WebDriverElement $parameterElement
     * @param string $parameterValueText
     * @return \Facebook\WebDriver\WebDriverElement
     */
    private function getLabelElementByParameterValueText($parameterElement, $parameterValueText)
    {
        $labelElements = $parameterElement->findElements(WebDriverBy::cssSelector('.js-product-filter-parameter-value'));

        foreach ($labelElements as $labelElement) {
            try {
                if (stripos($labelElement->getText(), $parameterValueText) !== false) {
                    return $labelElement;
                }
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $ex) {
                continue;
            }
        }

        $message = 'Unable to find parameter value with label "' . $parameterValueText . '" in product filter.';
        throw new \Facebook\WebDriver\Exception\NoSuchElementException($message);
    }
}
