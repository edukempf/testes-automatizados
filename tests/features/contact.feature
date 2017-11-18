Feature: Contact
  In order to change info from my profile
  As a ordinary user
  I need to be able to manage my contacts

  Rules:
  - I can add phones
  - I can add telephones
  - I can add duplicated data

  @importante
  Scenario Outline: Adding a Phone
    Given I am logged in:
      | login      | password   | name   |
      | <login>    | <password> | <name> |
    And I am seeing my contact data
    When I add a new phone
    Then I see a confirmation message

    Examples:
      | login      | password | name       |
      | unicesumar | 123456   | Unicesumar |
      | julio0001  | 123456   | Julio      |

  Scenario Outline: Adding a Email
    Given I am logged in:
      | login      | password   | name   |
      | <login>    | <password> | <name> |
    And I am seeing my contact data
    When I add a new email
    Then I see a confirmation message

  Examples:
  | login      | password | name       |
  | unicesumar | 123456   | Unicesumar |
  | julio0001  | 123456   | Julio      |