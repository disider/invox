Feature: Accountant can deselect the current company
  In order to finish viewing the company I'm accounting
  As an accountant
  I want to deselect the current company

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there are companies:
      | name  | owner              |
      | Acme1 | owner1@example.com |
      | Acme2 | owner1@example.com |
      | Bros  | owner2@example.com |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme1   |
      | accountant@example.com | Bros    |
    And I am logged as "accountant@example.com"
    When I visit "/companies/%companies.Acme1.id%/select"

  Scenario: I deselect currently selected company
    When I visit "/companies/close-current"
    Then I should see no "/companies/%companies.Acme1.id%/view" link
#    And I should see the "/companies/%companies.Acme1.id%/select" link
    And I should see no "/companies/%companies.Acme2.id%/view" link
#    And I should see the "/companies/%companies.Bros.id%/select" link
