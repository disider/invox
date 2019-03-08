Feature: Owner can edit his company
  In order to modify my company
  As an owner
  I want to edit my company details

  Background:
    Given there is a user:
      | email                  | role       |
      | user@example.com       | user       |
      | accountant@example.com | accountant |
    And there is a company:
      | name | owner            |
      | Acme | user@example.com |
    And there is a country:
      | code |
      | IT   |
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

  Scenario: I can view my company details
    When I visit "/companies/%companies.Acme.id%/edit"
    Then I should see the "company" fields:
      | name | Acme |

  Scenario: I can update my company
    When I visit "/companies/%companies.Acme.id%/edit"
    Then I can press "Save"

  Scenario: I update my company
    Given I visit "/companies/%companies.Acme.id%/edit"
    And I should see the "/quotes" link
    When I fill the "company" form with:
      | name | vatNumber   | fiscalCode       | phoneNumber | faxNumber  | address                | province | city | zipCode | addressNotes |
      | Bros | 01234567890 | DMASDL90A01Z114J | 0123456789  | 9876543210 | King's Cross Road, 123 | RM       | Rome | 01234   | 2nd floor    |
    And I uncheck the "Quote" field
    And I upload "test.png" in the "company.logo" field
    And I press "Save and close"
    Then I should be on "/dashboard"
    And I should see "Bros"
    And I should see no "/quotes" link

  Scenario: I cannot upload an invalid logo type
    Given I visit "/companies/%companies.Acme.id%/edit"
    When I upload "test.pdf" in the "company.logo" field
    And I press "Save and close"
    Then I should be on "/companies/%companies.Acme.id%/edit"
    And I should see a "Invalid image type" error
