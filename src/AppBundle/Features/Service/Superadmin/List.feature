Feature: Superadmin can list all his services
  In order to view his services
  As a superadmin
  I want to view the list of all services

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

  Scenario: I can add new services
    When I visit "/services"
    And I should see the "/services/new" link

  Scenario: I view all services
    Given there is a service:
      | name     | company |
      | Service1 | Acme    |
    When I visit "/services"
    Then I should see 1 "service"
    And I should see the "/services/new" link
