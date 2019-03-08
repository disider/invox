Feature: Accountant can list all his companies
  In order to manage the companies I've been connected to
  As an accountant
  I want to view the list of all the companies I manage

  Background:
    Given there are users:
      | email                  |
      | accountant@example.com |
      | owner1@example.com     |
      | owner2@example.com     |
    And there are companies:
      | name | owner              |
      | Acme | owner1@example.com |
      | Bros | owner2@example.com |
    And there is an accountant:
      | email                  | company |
      | accountant@example.com | Acme    |
    And I am logged as "accountant@example.com"

  Scenario: I view all the companies I manage
    When I visit "/companies"
    Then I should see 1 "company"
    And I should see no "/companies/%companies.Acme.id%/edit" link
    And I should see 2 link with class ".view"
    And I should see 0 links with class ".edit"
    And I should see 0 links with class ".delete"
    And I should see 1 link with class ".create"


