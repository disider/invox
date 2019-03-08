Feature: User adds a customer
  In order to add a customer
  As a user
  I want to add a customer filling a form

  Background:
    Given there is a user:
      | email            |
      | user@example.com |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a country:
      | code | name  |
      | IT   | Italy |
    And there is a province:
      | name | code | country |
      | Rome | RM   | IT      |
    And there is a city:
      | name | province |
      | Rome | Rome     |
    And there is a zip code:
      | code  | city |
      | 01234 | Rome |
    And I am logged as "user@example.com"
    When I visit "/customers/new"

  Scenario: I can add a customer
    Then I can press "Save"
    And I can press "Save and close"

  Scenario: I add a public customer
    Given I fill the "customer" form with:
      | name | vatNumber   | referent     | email                 | phoneNumber | faxNumber  | address                | province | city | zipCode | addressNotes | notes          |
      | Bros | 01234567890 | Adam Sandler | adam.sandler@bros.com | 0123456789  | 9876543210 | King's Cross Road, 123 | RM       | Rome | 01234   | 2nd floor    | Great customer |
    And I select the "customer.country" option "Italy"
    When I press "Save and close"
    Then I should be on "/customers"
    And I should see 1 "customer"

  Scenario: I add a private customer
    Given I fill the "customer" form with:
      | name | vatNumber   | fiscalCode       | referent     | email                 | phoneNumber | faxNumber  | address                | province | city | zipCode | addressNotes | notes          |
      | Bros | 01234567890 | DMASDL90A01Z114J | Adam Sandler | adam.sandler@bros.com | 0123456789  | 9876543210 | King's Cross Road, 123 | RM       | Rome | 01234   | 2nd floor    | Great customer |
    And I select the "customer.country" option "Italy"
    When I press "Save and close"
    Then I should be on "/customers"
    And I should see 1 "customer"

  Scenario: I cannot add a customer without mandatory details
    When I press "Save and close"
    Then I should be on "/customers/new"
    And I should see an "Empty name" error
#    And I should see an "Empty VAT number" error

  Scenario Outline: I cannot add a customer with invalid details
    Given I fill the "customer" form with:
      | name | vatNumber   | email   | province   | city   | zipCode   |
      | Bros | <vatNumber> | <email> | <province> | <city> | <zipCode> |
    When I press "Save and close"
    Then I should be on "/customers/new"
    And I should see an "<error>" error

    Examples:
      | vatNumber | email      | province | city | zipCode | error              |
      | ABCDE     |            |          |      |         | Invalid VAT number |
      |           | wrongemail |          |      |         | Invalid email      |
#      |           | Unknown  |      |         | Unknown province   |
#      |           |          | Unknown |         | Unknown city       |
#      |           |          |         | Unknown | Unknown zip code   |
