Feature: Owner can select the current company
  In order to change the company I'm managing
  As an owner
  I want to select the current company

  Background:
    Given there is a user:
      | email             | role  |
      | owner@example.com | owner |
    And there are companies:
      | name  | owner             |
      | Acme1 | owner@example.com |
      | Acme2 | owner@example.com |
    And I am logged as "owner@example.com"

  Scenario: I see no currently selected company
    When I visit "/"
    Then I should see no "/companies/%companies.Acme1.id%/edit" link
    And I should see no "/companies/%companies.Acme2.id%/edit" link

  Scenario: I select a company
    When I visit "/companies/%companies.Acme2.id%/select"
    Then I should see no "/companies/%companies.Acme1.id%/edit" link
    And I should see the "/companies/%companies.Acme2.id%/edit" link
