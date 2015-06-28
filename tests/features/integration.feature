@javascript
Feature: PhantomJS Driver behat integration
  In order to use the driver in behat
  As a developer
  I need to test that the driver integrates correctly with behat

  Scenario: Basic web page visit
    Given I am on "/"
    Then the response status code should be 200
    And I should see "Dhaka"
