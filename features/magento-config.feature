Feature: Rewrites are returned
    In order to be warned on rewrites
    As a magento developer
    I need to be able to see what rewrites I am using in my extension

    Scenario: Model rewrites are detected for Catalog/Image
        Given I have a magento config file with "Model" rewrites
        When I run an inspection
        Then I should see be warned that there are rewrites

    Scenario: Controller rewrites are detected for Mage/Tag
        Given I have a magento config file with "Controller" rewrites
        When I run an inspection
        Then I should see be warned that there are rewrites

    Scenario: No rewrites are detected
        Given I have a magento config file with "No" rewrites
        When I run an inspection
        Then I should not see any errors
