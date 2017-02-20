Feature: User can list medium

  Background:
    Given there is a user:
      | email             |
      | user1@example.com |
      | user2@example.com |
    And there is a company:
      | name | owner             |
      | Acme | user1@example.com |
      | Bros | user2@example.com |
    And I am logged as "user1@example.com"

  Scenario: I see the media list
    Given there are media:
      | fileName  | fileUrl  | company |
      | fileName0 | fileUrl0 | Acme    |
      | fileName1 | fileUrl1 | Acme    |
      | fileName2 | fileUrl2 | Bros    |
    When I visit "/media"
    Then I should see 2 "medium"
    And I should see the "/media/new" link
    And I should see the "/media/%media.fileName0.id%/edit" link
    And I should see the "/media/%media.fileName1.id%/edit" link
    And I should see no "/media/%media.fileName2.id%/edit" link


#
#  Scenario: I see the medium list paginated
#    Given there are 12 media with:
#      | fileName  | fileUrl  |
#      | fileName0 | fileUrl1 |
#    When I visit "/media?page=3"
#    Then I should see 2 "medium"
#
#  Scenario: I can filter media by file name
#    Given there are media:
#      | fileName       | fileUrl       | company |
#      | fileName0       | fileUrl1       | Acme    |
#      | Other fileName0 | Other fileUrl1 | Acme    |
#    And I am on "/media"
#    When I fill the "media_filter.fileName" field with "Other fileName0"
#    And I press "Filter"
#    Then I should be on "/media"
#    And I should see 1 "medium"
#    And I should see "Other fileName0"
#
#  Scenario: I can filter media by file url
#    Given there are media:
#      | fileName       | fileUrl       | company |
#      | fileName0       | fileUrl1       | Acme    |
#      | Other fileName0 | Other fileUrl1 | Acme    |
#    And I am on "/media"
#    When I fill the "media_filter.fileUrl" field with "Other fileUrl1"
#    And I press "Filter"
#    Then I should be on "/media"
#    And I should see 1 "medium"
#    And I should see "Other fileUrl1"
#