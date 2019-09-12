Feature:
  Background:
    Given I am an organizer

  Scenario: Scheduling a meetup
    When I schedule a "Coding Dojo" on "2020-05-30" at "20:00"
    Then there will be an upcoming meetup called "Coding Dojo"
