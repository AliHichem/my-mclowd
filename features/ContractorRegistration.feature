@registration
Feature: Contractor Registration
    As a contractor
    I want to register to the page
    So I can search for jobs

  Scenario: Sending form with valid data
    Given countries are loaded
    And hearsources are loaded
    When I am on "/register/contractor"
     And I fill in the following:
      | fos_user_registration_form[fullName]              | Full Name                   |
      | fos_user_registration_form[username]              | defrag                      |
      | fos_user_registration_form[email]                 | michal.dabrowski@trisoft.ro |
      | fos_user_registration_form[plainPassword][first]  | 123456                      |
      | fos_user_registration_form[plainPassword][second] | 123456                      |
      | fos_user_registration_form[city]                  | Sidney                      |
      | fos_user_registration_form[accountType]           | individual                  |
      | fos_user_registration_form[displayName]           | Display Name                |
     And I select "Google" from "fos_user_registration_form[hearSource]"
     And I select "Australia" from "fos_user_registration_form[country]"
     And I press "contractor-register-submit"
    Then the response status code should be 200
     And the response should contain "Congratulations"