Feature: Sales agent can show a company details
  In order to manage the companies I've been connected to
  As a sales agent
  I want to view the details of a company I manage

  Background:
    Given there are users:
      | email                   | role        |
      | sales_agent@example.com | sales_agent |
      | owner1@example.com      | owner       |
      | owner2@example.com      | owner       |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is a sales agent:
      | email                   | company |
      | sales_agent@example.com | Acme    |
    And I am logged as "sales_agent@example.com"

  Scenario: I view all the companies I manage
    When I visit "/companies/%companies.Acme.id%/view"
    Then I should see "Acme"
