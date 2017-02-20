Feature: Sales agent can deselect the current company
  In order to finish viewing the company I'm sales
  As a sales agent
  I want to deselect the current company

  Background:
    Given there is a user:
      | email                  |
      | sales_agent@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there are companies:
      | name  | owner              |
      | Acme1 | owner1@example.com |
      | Acme2 | owner1@example.com |
      | Bros  | owner2@example.com |
    And there is a sales agent:
      | email                  | company |
      | sales_agent@example.com | Acme1   |
      | sales_agent@example.com | Bros    |
    And I am logged as "sales_agent@example.com"
    When I visit "/companies/%companies.Acme1.id%/select"

  Scenario: I deselect currently selected company
    When I visit "/companies/close-current"
    Then I should see no "/companies/%companies.Acme1.id%/view" link
    And I should see no "/companies/%companies.Acme2.id%/view" link
