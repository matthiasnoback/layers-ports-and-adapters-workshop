Feature:

  Scenario: Scheduling a meetup
    When I schedule a "Coding Dojo" with the description "Test" on "2018-05-30"
    Then there will be an upcoming meetup called "Coding Dojo" scheduled for "2018-05-30"
