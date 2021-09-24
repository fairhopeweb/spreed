Feature: set-publishing-permissions
  Background:
    Given user "owner" exists
    Given user "moderator" exists
    Given user "invited user" exists
    Given user "not invited user" exists
    Given user "not invited but joined user" exists
    Given user "not joined user" exists

  Scenario: Owner and moderators can set default permissions users can not
    Given user "owner" creates room "group room" (v4)
      | roomType | 2 |
      | roomName | room |
    And user "owner" adds user "moderator" to room "group room" with 200 (v4)
    And user "owner" promotes "moderator" in room "group room" with 200 (v4)
    And user "owner" adds user "invited user" to room "group room" with 200 (v4)
    When user "owner" sets default permissions for room "group room" to "S" with 200 (v4)
    When user "moderator" sets default permissions for room "group room" to "AV" with 200 (v4)
    When user "invited user" sets default permissions for room "group room" to "D" with 403 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | moderator    | SJLAVP      |
      | users      | invited user | CAV         |

  Scenario: Owner and moderators can set call permissions users can not
    Given user "owner" creates room "group room" (v4)
      | roomType | 2 |
      | roomName | room |
    And user "owner" adds user "moderator" to room "group room" with 200 (v4)
    And user "owner" promotes "moderator" in room "group room" with 200 (v4)
    And user "owner" adds user "invited user" to room "group room" with 200 (v4)
    When user "owner" sets call permissions for room "group room" to "S" with 200 (v4)
    When user "moderator" sets call permissions for room "group room" to "AV" with 200 (v4)
    When user "invited user" sets call permissions for room "group room" to "D" with 403 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | moderator    | SJLAVP      |
      | users      | invited user | CAV         |

  Scenario: User setting over call setting over conversation setting over default
    Given user "owner" creates room "group room" (v4)
      | roomType | 2 |
      | roomName | room |
    And user "owner" adds user "invited user" to room "group room" with 200 (v4)
    When user "owner" sets default permissions for room "group room" to "S" with 200 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | invited user | CS          |
    When user "owner" sets call permissions for room "group room" to "A" with 200 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | invited user | CA          |
    And user "owner" sets permissions for "invited user" in room "group room" to "V" with 200 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | invited user | CV          |
    And user "owner" sets permissions for "invited user" in room "group room" to "D" with 200 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | invited user | CA          |
    When user "owner" sets call permissions for room "group room" to "D" with 200 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | invited user | CS          |
    When user "owner" sets default permissions for room "group room" to "D" with 200 (v4)
    Then user "owner" sees the following attendees in room "group room" with 200 (v4)
      | actorType  | actorId      | permissions |
      | users      | owner        | SJLAVP      |
      | users      | invited user | SJAVP       |
