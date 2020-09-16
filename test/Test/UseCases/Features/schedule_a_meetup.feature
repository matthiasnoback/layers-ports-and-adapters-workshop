Feature:

  Background:
    Given I am an organizer

  Scenario: Scheduling a meetup
    When I schedule a "Coding Dojo" on "2024-05-30" at "20:00"
    Then there will be an upcoming meetup called "Coding Dojo"

  Scenario: Cancelling a meetup
    Given I have scheduled a "Coding Dojo" on "2024-05-30" at "20:00"
    When I cancel it
    Then it will no longer show up in the list of upcoming meetups
