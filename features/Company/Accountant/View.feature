Feature: Accountant can show a company details
  In order to manage the companies I've been connected to
  As an accountant
  I want to view the details of a company I manage

  Background:
    Given there is a user:
      | email                  | role       |
      | accountant@example.com | accountant |
      | owner1@example.com     | owner      |
      | owner2@example.com     | owner      |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And I am logged as "accountant@example.com"

  Scenario: I view all the companies I manage
    When I visit "/companies/%companies.Acme.id%/view"
    Then I should see "Acme"
