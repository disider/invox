Feature: Superadmin can edit a service
  In order to modify a service
  As a superadmin
  I want to edit service details

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

  Scenario: I can view a service details
    When I visit "/services/%services.Service1.id%/edit"
    Then I should see the "service" fields:
      | name | Service1 |

  Scenario: I can update a service
    When I visit "/services/%services.Service1.id%/edit"
    Then I can press "Save"

  Scenario: I update a service
    When I visit "/services/%services.Service1.id%/edit"
    And I fill the "service" fields with:
      | name | New name |
    And I press "Save and close"
    Then I should be on "/services"
    And I should see "New name"

  Scenario: I cannot edit an undefined service
    When I try to visit "/services/0/edit"
    Then the response status code should be 404
