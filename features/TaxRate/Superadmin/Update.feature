Feature: Superadmin can edit a tax rate
  In order to modify a tax rate
  As a superadmin
  I want to edit tax rate details

  Background:
    Given there is a user:
      | email                  | role       |
      | superadmin@example.com | superadmin |
    And there is a tax rate:
      | name    | amount |
      | VAT 10% | 10     |
    And I am logged as "superadmin@example.com"

  Scenario: I can view a tax rate details
    When I visit "/tax-rates/%taxRates.last.id%/edit"
    Then I should see the "taxRate" fields:
      | amount               | 10.00   |
      | translations.en.name | VAT 10% |

  Scenario: I can update a tax rate
    When I visit "/tax-rates/%taxRates.last.id%/edit"
    Then I can press "Save"

  Scenario: I update a tax rate
    When I visit "/tax-rates/%taxRates.last.id%/edit"
    And I fill the "taxRate" fields with:
      | amount               | 22      |
      | translations.en.name | VAT 22% |
      | translations.it.name | IVA 22% |
    And I press "Save and close"
    Then I should be on "/tax-rates"
    And I should see "22%"

  Scenario: I cannot edit an undefined tax rate
    When I try to visit "/tax-rates/0/edit"
    Then the response status code should be 404
