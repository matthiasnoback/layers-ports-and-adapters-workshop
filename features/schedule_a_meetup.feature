Feature:

  Scenario: Schedule a meetup
    Given I am on "/schedule-meetup"
    When I fill in the following:
      | Name              | Coding Dojo      |
      | Description       | Some description |
      | Schedule for date | 2018-10-10       |
      | Time              | 20:00            |
    And I press "Schedule this meetup"
    And I am on "/"
    Then I should see "Upcoming meetups"
    And I should see "Coding Dojo"
