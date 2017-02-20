Feature: Accountant can refuse an invite
  In order to account a company
  As an accountant
  I want to refuse the company invite

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
    Given there is an accountant invite:
      | email                   | company |
      | accountant1@example.com | Acme1   |
    And I am logged as "accountant1@example.com"

  Scenario: I refuse an invite
    When I visit "/invites/%invites.last.token%/refuse"
    Then I should be on "/dashboard"
    And I should see no "/companies/%companies.Acme1.id%/select" link
    And I should see no "/invites" link

  Scenario: I cannot refuse an unknown invite
    When I try to visit "/invites/ABCDE/refuse"
    Then the response status code should be 404

  Scenario: I cannot refuse an invite not for me
    Given there is an accountant invite:
      | email                   | company |
      | accountant2@example.com | Acme2   |
    When I try to visit "/invites/%invites.last.token%/refuse"
    Then the response status code should be 403
