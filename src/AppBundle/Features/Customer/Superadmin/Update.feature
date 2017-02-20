Feature: Superadmin updates any customer
  In order to modify a customer
  As a superadmin
  I want to edit customer details

  Background:
    Given there are users:
      | email                  | role       |
      | superadmin@example.com | superadmin |
      | user@example.com       | user       |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
      | Bros | user@example.com |
    And there is a customer:
      | name     | email                | company | address                    | vatNumber  |
      | Customer | customer@example.com | Acme    | London Street, 12345, York | 9876543210 |
    And I am logged as "superadmin@example.com"
    And I visit "/companies/%companies.Acme.id%/select"

  Scenario: I can view any customer details
    When I visit "/customers/%customers.last.id%/edit"
    Then I should see the "customer" fields:
      | name      | Customer                   |
      | email     | customer@example.com       |
      | address   | London Street, 12345, York |
      | vatNumber | 9876543210                 |

  Scenario: I can update any customer
    When I visit "/customers/%customers.last.id%/edit"
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I update any customer
    When I visit "/customers/%customers.last.id%/edit"
    And I select the "customer.company" option "Bros"
    And I press "Save"
    Then I should see the "customer.company" option "Bros" selected
