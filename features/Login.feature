Feature: Login to system
  As a Registered user 
  I want to login to system
  So i can use my specific application functions

  Background:   
    Given I am on "/login"
    And the following people exist:
        | username      | email                       | password      | type     |
        | defrag        | michal.dabrowski@trisoft.ro | 12345         | client   |
        
  Scenario: Signin into system with valid login and password 
    Given I fill in "username" with "defrag"
    And I fill in "password" with "12345"
    When I press "Login"
    Then I am logged in system
      