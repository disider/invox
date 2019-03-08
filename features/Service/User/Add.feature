Feature: User can add a service
  In order to add a new service
  As a user
  I want to add a service filling a form

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And I am logged as "user@example.com"
    When I visit "/services/new"

  Scenario: I can add a service
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a service
    Given I fill the "service" fields with:
      | name      | Service1 |
      | code      | PR1      |
      | unitPrice | 10       |
    When I press "Save and close"
    Then I should be on "/services"
    And I should see 1 "service"
    And I should see the "service" rows:
      | name     | code |
      | Service1 | PR1  |

  Scenario: I cannot add a service without mandatory fields
    When I press "Save and close"
    Then I should see the "service" form errors:
      | code      | Empty code       |
      | name      | Empty name       |
      | unitPrice | Empty unit price |


  Scenario: I cannot add a service without name
    When I press "Save and close"
    Then I should be on "/services/new"
    And I should see a "Empty name" error
