<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use MagentoRewrites\Inspect;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
    * @var string
    */
    private $fixture;

    private $output;

    /**
     * @Given I have a magento config file with :type rewrites
     */
    public function iHaveAMagentoConfigFileWithRewrites($type)
    {
        $this->fixture = strtolower($type);
    }

    /**
     * @When I run an inspection
     */
    public function iRunAnInspection()
    {
        $file = file_get_contents(__DIR__ . '/fixtures/mage_' . $this->fixture . '_config.xml');

        $inspector = new MagentoRewrites\Inspect;
        $this->output = $inspector->run($file);
    }

    /**
     * @Then I should see be warned that there are rewrites
     */
    public function iShouldSeeBeWarnedThatThereAreRewrites()
    {
        expect($this->output)->notToBe(null);
    }

    /**
     * @Then I should not see any errors
     */
    public function iShouldNotSeeAnyErrors()
    {
        expect(count($this->output))->toBe(0);
    }

    /**
     * @Then I should see both :controller and :model errors
     */
    public function iShouldSeeBothAndErrors()
    {
        expect(array_key_exists('controller', $this->output))->toBe(true);
        expect(array_key_exists('other', $this->output))->toBe(true);
    }
}
