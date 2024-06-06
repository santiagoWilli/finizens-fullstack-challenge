Feature: Get pending orders
  In trade allocations
  system should be able
  to get the pending orders

  Scenario: Get pending orders
    Given I send a PUT request to "/api/portfolios/1" with body:
    """
    {
      "allocations": [
        {
          "id": 1,
          "shares": 3
        },
        {
          "id": 2,
          "shares": 4
        }
      ]
    }
    """
    And the response status code should be 200
    And the response should be empty
    And I send a POST request to "/api/orders" with body:
    """
    {
      "id": 1,
      "portfolio": 1,
      "allocation": 3,
      "shares": 10,
      "type": "buy"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a POST request to "/api/orders" with body:
    """
    {
      "id": 2,
      "portfolio": 1,
      "allocation": 1,
      "shares": 3,
      "type": "sell"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a POST request to "/api/orders" with body:
    """
    {
      "id": 3,
      "portfolio": 1,
      "allocation": 1,
      "shares": 5,
      "type": "buy"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a PATCH request to "/api/orders/2" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a GET request to "/api/orders?portfolio=1&completed=false"
    Then the response status code should be 200
    And the response body should be:
    """
    [
      {
        "id": 1,
        "portfolio": 1,
        "allocation": 3,
        "shares": 10,
        "type": "buy"
      },
      {
        "id": 3,
        "portfolio": 1,
        "allocation": 1,
        "shares": 5,
        "type": "buy"
      }
    ]
    """

  Scenario: Invalid query params
    Given I send a GET request to "/api/orders?allocation=1"
    Then the response status code should be 400
    And the response should be empty
