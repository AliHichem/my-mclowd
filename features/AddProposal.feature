@Proposals
Feature: Add proposal for job
  In order to get a job
  As a contractor
  I need to make a proposal

Background:
    Given the following people exist:
        | username      | email                       | password      | type         |
        | udan          | dan.ursoviciu@trisoft.ro    | 12345         | contractor   |
    And I am logged in as "udan" with password "12345"
    And the following jobs exist:
        | name            | description        |
        | Test job        | Test decription    |

  Scenario: Submit proposal
    Given I am on "/tasks/1/test-job"
    When I follow "Make Proposal"
    And I fill in the following:
        | new_proposal[description]  | Will work for food |
        | new_proposal[hours]        | 10                 |
        | new_proposal[duration]     | 1                  |
        | new_proposal[rate]         | 20                 |
    And I press "Make Proposal"
    Then the response status code should be 200
