@jobs
Feature: Creating jobs as client
    As a client
    I want to post jobs
    So I can find contractors

  Scenario: Entering creation page unauthenticated
    Given I do not follow redirects
    When I go to "/jobs/new"    
    Then I should be redirected to "/login"

  Scenario: Sending form with valid data
    Given users table is empty        
    And the following people exist:
        | username      | email                       | password      | type     |
        | defrag        | michal.dabrowski@trisoft.ro | 12345         | client   |
    And I am logged in as "defrag" with password "12345"       
    When I am on "/jobs/new"    
        And I fill in the following:
            | new_job[name]              | Will work for food |
            | new_job[description]       | Tessting |
            
    And I press "Create Job"
    Then the response status code should be 200
    And the response should contain "You have added a job"
