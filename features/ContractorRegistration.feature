@registration
Feature: Contractor Registration
    As a contractor
    I want to register to the page
    So I can search for jobs

  Scenario: Sending form with valid data
    Given users table is empty
    When I am on "/register/client"
        And I fill in the following:
            | fos_user_registration_form[username]              | defrag |
            | fos_user_registration_form[email]                 | michal.dabrowski@trisoft.ro |
            | fos_user_registration_form[plainPassword][first]  | 123456 |
            | fos_user_registration_form[plainPassword][second] | 123456 |    
    And I press "registration.submit"
    Then the response status code should be 200
    And the response should contain "Congratulations"