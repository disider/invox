Feature: Sales agent can select the current company
  In order to change the company I'm sales agent
  As a sales agent
  I want to select the current company

  Background:
    Given there are users:
      | email                   | role        |
      | sales_agent@example.com | sales_agent |
      | owner1@example.com      | owner       |
      | owner2@example.com      | owner       |
    And there are companies:
      | name  | owner              |
      | Acme1 | owner1@example.com |
      | Acme2 | owner1@example.com |
      | Bros  | owner2@example.com |
    And there is a sales agent:
      | email                   | company |
      | sales_agent@example.com | Acme1   |
      | sales_agent@example.com | Bros    |
    And I am logged as "sales_agent@example.com"

  Scenario: I see no currently selected company
    When I visit "/"
    Then I should see no "/companies/%companies.Acme1.id%/view" link
    And I should see no "/companies/%companies.Acme2.id%/view" link

  Scenario: I see the currently selected company
    When I visit "/companies/%companies.Acme1.id%/select"
    Then I should see the "/companies/%companies.Acme1.id%/view" link
    And I should see no "/companies/%companies.Acme2.id%/view" link
    And I should see no "/customers" link
    And I should see no "/quotes" link
    And I should see no "/invoices" link
    And I should see no "/orders" link
    And I should see no "/credit-notes" link
    And I should see no "/petty-cash-notes" link
    And I should see no "/products" link
    And I should see no "/services" link
    And I should see the "/paragraph-templates" link
    And I should see the "/working-notes" link

  Scenario: I select another company
    When I visit "/companies/%companies.Bros.id%/select"
    Then I should see no "/companies/%companies.Acme1.id%/view" link
    And I should see the "/companies/%companies.Bros.id%/view" link

  Scenario: I cannot select a company I'm not accounting
    When I try to visit "/companies/%companies.Acme2.id%/select"
    Then the response status code should be 403
