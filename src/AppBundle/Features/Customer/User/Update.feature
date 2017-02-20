Feature: User updates a customer
  In order to modify a customer
  As an user
  I want to edit customer details

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a customer:
      | name     | email                | company | address                    | vatNumber   |
      | Customer | customer@example.com | Acme    | London Street, 12345, York | 09876543210 |
    And I am logged as "user@example.com"

  Scenario: I can view a customer details
    When I visit "/customers/%customers.last.id%/edit"
    Then I should see the "customer" fields:
      | name      | Customer                   |
      | email     | customer@example.com       |
      | address   | London Street, 12345, York |
      | vatNumber | 09876543210                |

  Scenario: I can update a customer
    When I visit "/customers/%customers.last.id%/edit"
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I update a user
    When I visit "/customers/%customers.last.id%/edit"
    And I fill the "customer" form with:
      | name         | email                |
      | Updated name | newemail@example.com |
    And I press "Save"
    Then I should see the "customer" fields:
      | name  | Updated name         |
      | email | newemail@example.com |
    And I should see "successfully updated"
