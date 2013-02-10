@messages
Feature: Send Message
    As a client
    I want to send a message

  Background:
    Given users table is empty
    And the following people exist:
        | username      | email                       | password      | type     |
        | defrag        | michal.dabrowski@trisoft.ro | 12345         | client   |
        | udan          | dan.ursoviciu@trisoft.ro    | 12345         | client   |

  Scenario: Sending a message
    Given I am logged in as "udan" with password "12345"
    When I am on "/messages/new"
        And I fill in the following:
            | message[recipient]              | defrag |
            | message[subject]                | Test message |
            | message[body]                   | Testing  the send message function |
    When I press ""
    Then the response status code should be 200
    When I go to "/messages/sent"
    Then the response should contain "Test message"