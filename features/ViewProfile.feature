@profile 
Feature: View Profile
   In order to remember my name,
   As a client
   I want to be able to view my profile

   Scenario: View Own Name
      Given I am logged in as client with:
        | fullName      | city    |
        | Mihai Viteazu | Suceava |
      When I go to "/profile"
      Then I should see "Mihai"
      And I should see "Suceava"

   Scenario: View My Own Tasks
      Given the following people exist:
        | username      | email                       | password      | type     |
        | defrag        | michal.dabrowski@trisoft.ro | 12345         | client   |
        | udan          | dan.ursoviciu@trisoft.ro    | 12345         | client   |
      And the following tasks exist:
        | name       | isActive   | user       |
        | first      | true       | defrag     |
        | second     | true       | udan       |
        | third      | false      | udan       |
      And I am logged in as "udan" with password "12345"
      When I go to "/profile"
      Then I should see "second"
      And I should see "third"
      But I should not see "first"
