Feature: Accountant can accept an invite
  In order to account a company
  As an accountant
  I want to accept the company invite

  Background:
    Given there is a user:
      | email                   | role       |
      | accountant1@example.com | accountant |
      | accountant2@example.com | accountant |
      | owner1@example.com      | owner      |
      | owner2@example.com      | owner      |
    And there are companies:
      | name  | owner              |
      | Acme1 | owner1@example.com |
      | Acme2 | owner1@example.com |
      | Bros  | owner2@example.com |

  Scenario: I accept an invite
    Given there is an accountant invite:
      | email                   | company |
      | accountant1@example.com | Acme1   |
    And I am logged as "accountant1@example.com"
    When I visit "/invites/%invites.last.token%/accept"
    Then I should be on "/dashboard"
    And I should see no "/invites" link

  Scenario: I see the company I've been invited to
    Given there is an accountant invite:
      | email                   | company |
      | accountant1@example.com | Acme1   |
    And I am logged as "accountant1@example.com"
    And I visit "/invites/%invites.last.token%/accept"
    When I visit "/companies"
    Then I should see the "/companies/%companies.Acme1.id%/view" link

  Scenario: I cannot accept an unknown invite
    Given I am logged as "accountant1@example.com"
    When I try to visit "/invites/ABCDE/accept"
    Then the response status code should be 404

  Scenario: I cannot accept an invite not for me
    Given there is an accountant invite:
      | email                   | company |
      | accountant2@example.com | Acme2   |
    And I am logged as "accountant1@example.com"
    When I try to visit "/invites/%invites.last.token%/accept"
    Then the response status code should be 403

  Scenario: I see the company when I register and accept an invite
    Given there is an accountant invite:
      | email                   | company |
      | accountant3@example.com | Acme1   |
    And I visit "/invites/%invites.last.token%/view"
    And I fill the "registration" form with:
      | email                   | password |
      | accountant3@example.com | secret   |
    And I press "Register"
    And I visit "/register/confirm/%users.last.confirmationToken%"
    And I visit "/invites/%invites.last.token%/accept"
    When I visit "/companies"
    Then I should see the "/companies/%companies.Acme1.id%/view" link
