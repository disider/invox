Feature: Accountant can select the current company
  In order to change the company I'm accounting
  As an accountant
  I want to select the current company

  Background:
    Given there is a user:
      | email                  | role       |
      | accountant@example.com | accountant |
      | owner1@example.com     | owner      |
      | owner2@example.com     | owner      |
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

  Scenario: I see no currently selected company
    When I visit "/"
    Then I should see no "/companies/%companies.Acme1.id%/view" link
    And I should see no "/companies/%companies.Acme2.id%/view" link

  Scenario: I see the currently selected company
    When I visit "/companies/%companies.Acme1.id%/select"
    Then I should see the "/companies/%companies.Acme1.id%/view" link
    And I should see no "/companies/%companies.Acme1.id%/select" link
    And I should see no "/companies/%companies.Acme2.id%/view" link
    And I should see no "/customers" link
    And I should see no "/quotes" link
    And I should see the "/invoices" link
    And I should see no "/orders" link
    And I should see the "/credit-notes" link
    And I should see the "/petty-cash-notes" link
    And I should see no "/products" link
    And I should see no "/services" link

  Scenario: I select another company
    When I visit "/companies/%companies.Bros.id%/select"
    Then I should see no "/companies/%companies.Acme1.id%/view" link
    And I should see the "/companies/%companies.Bros.id%/view" link

  Scenario: I cannot select a company I'm not accounting
    When I try to visit "/companies/%companies.Acme2.id%/select"
    Then the response status code should be 403
