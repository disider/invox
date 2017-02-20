Feature: Superadmin can delete any service
  In order to delete a service
  As a superadmin
  I want to delete a service

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a service:
      | name     | company |
      | Service1 | Acme    |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I can delete any service
    When I visit "/services/%services.Service1.id%/delete"
    Then I should be on "/services"
    And I should see 0 "service"
