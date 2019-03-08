Feature: Owner can add a company
  In order to add a company
  As an owner
  I want to add a company filling a form

  Background:
    Given there is a user:
      | email             |
      | owner@example.com |
    And there is a country:
      | code |
      | IT   |
    And there is a document template:
      | name |
      | T1   |
    And I am logged as "owner@example.com"
    When I visit "/companies/new"

  Scenario: I can add a company
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a company
    Given I fill the "company" fields with:
      | name      | Acme        |
      | vatNumber | 01234567890 |
    When I press "Save and close"
    Then I should be on "/dashboard"
    And I should see the "/accounts" link
    And I should see the "/customers" link
    And I should see no "/cities" link
    And I should see no "/companies" link
    And I should see no "/countries" link
    And I should see the "/customers" link
    And I should see the "/quotes" link
    And I should see the "/invoices" link
    And I should see the "/orders" link
    And I should see the "/credit-notes" link
    And I should see no "/invites" link
    And I should see no "/logs" link
    And I should see the "/petty-cash-notes" link
    And I should see no "/products" link
    And I should see no "/pages" link
    And I should see the "/profile" link
    And I should see no "/provinces" link
    And I should see no "/services" link
    And I should see no "/users" link
    And I should see no "/tax-rates" link
    And I should see no "/zip-codes" link
    And I should see no "/companies/new" link
    And I should see no "/companies/%companies.last.id/edit" link

  Scenario: I cannot add a company without name
    When I press "Save and close"
    Then I should be on "/companies/new"
    And I should see a "Empty name" error
