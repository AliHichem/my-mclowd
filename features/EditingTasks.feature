@jobs
Feature: Editing tasks as client
  As a client
  I want to edit tasks    
    
  Scenario: Entering editing page unauthenticated
    Given I do not follow redirects
    When I go to "/tasks/my"    
    Then I should be redirected to "/"

  Scenario: Entering editing page as contractor
    Given users table is empty
    And the following people exist:
        | username      | email                       | password      | type         |
        | udan          | dan.ursoviciu@trisoft.ro    | 12345         | contractor   |
    And I am logged in as "udan" with password "12345"
    Given I do not follow redirects
    When I go to "/tasks/my"    
    Then I should be redirected to "/"
