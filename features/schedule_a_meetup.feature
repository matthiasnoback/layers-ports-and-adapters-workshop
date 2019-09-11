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

  Scenario: RSVP to a meetup
    Given I am logged in as "Regular user"
    And a meetup was scheduled
    And I am on the detail page of this meetup
    When I press "RSVP"
    Then I should see "You have successfully RSVP-ed to this meetup"
    And the list of attendees should contain "Regular user"
