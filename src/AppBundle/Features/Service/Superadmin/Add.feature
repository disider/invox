Feature: Superadmin can add a service
  In order to add a new service
  As an superadmin
  I want to add a service filling a form for any company

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"
    When I visit "/services/new"

  Scenario: I can add a service
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a service
    Given I fill the "service" fields with:
      | code      | PR1      |
      | name      | Service1 |
      | unitPrice | 10       |
    And I select the "service.company" option "Acme"
    When I press "Save and close"
    Then I should be on "/services"
    And I should see 1 "service"
