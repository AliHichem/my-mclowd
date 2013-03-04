@jobs
Feature: Creating tasks as client
    As a client
    I want to post tasks
    So I can find contractors which im in need of

  Scenario: Entering creation page unauthenticated
    Given I do not follow redirects
    When I go to "/tasks/new"    
    Then I should be redirected to "/login"

  Scenario: Sending form with valid data
    Given users table is empty        
    And the following people exist:
        | username      | email                       | password      | type     |
        | defrag        | michal.dabrowski@trisoft.ro | 12345         | client   |
    And I am logged in as "defrag" with password "12345"   
    And the default categories are in database    
    When I am on "/tasks/new"    
        And I fill in the following:
            | task[name]              | Will work for food |
            | task[description]       | Tessting |
        And I select "Accounting" from "task[category]"        
        And I select "Ongoing" from "task[timePeriod]"        
    And I press "Create Job"
    Then the response status code should be 200
    And the response should contain "Task have been created"
