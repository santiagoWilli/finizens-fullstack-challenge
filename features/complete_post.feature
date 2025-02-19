Feature: Complete an active order
  In trade allocations
  system should be able
  to complete orders created

  Scenario: Complete a buy order for a new allocation
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
    And I send a PATCH request to "/api/orders/1" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a GET request to "/api/portfolios/1"
    Then the response status code should be 200
    And the response body should be:
    """
     {
      "id": 1,
      "allocations": [
        {
          "id": 1,
          "shares": 3
        },
        {
          "id": 2,
          "shares": 4
        },
        {
          "id": 3,
          "shares": 10
        }
      ]
    }
    """

  Scenario: Complete a buy order for an existing allocation
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
      "allocation": 1,
      "shares": 20,
      "type": "buy"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a PATCH request to "/api/orders/1" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a GET request to "/api/portfolios/1"
    Then the response status code should be 200
    And the response body should be:
    """
     {
      "id": 1,
      "allocations": [
        {
          "id": 1,
          "shares": 23
        },
        {
          "id": 2,
          "shares": 4
        }
      ]
    }
    """

  Scenario: Complete a sell order that keeps the shares above zero for the given allocation
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
      "allocation": 2,
      "shares": 2,
      "type": "sell"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a PATCH request to "/api/orders/1" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a GET request to "/api/portfolios/1"
    Then the response status code should be 200
    And the response body should be:
    """
     {
      "id": 1,
      "allocations": [
        {
          "id": 1,
          "shares": 3
        },
        {
          "id": 2,
          "shares": 2
        }
      ]
    }
    """

  Scenario: Complete a sell order that hits zero shares for the given allocation
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
      "allocation": 2,
      "shares": 4,
      "type": "sell"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a PATCH request to "/api/orders/1" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a GET request to "/api/portfolios/1"
    Then the response status code should be 200
    And the response body should be:
    """
     {
      "id": 1,
      "allocations": [
        {
          "id": 1,
          "shares": 3
        }
      ]
    }
    """

  Scenario: A unknown order
    Given I send a PATCH request to "/api/orders/401" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 404
    And the response should be empty

  Scenario: Invalid Method
    Given I send a DELETE request to "/api/orders/1"
    Then the response status code should be 405
    And the response should be empty

  Scenario: buy invalid payload
    Given I send a PATCH request to "/api/orders/1"
    Then the response status code should be 400
    And the response should be empty

  Scenario: Complete a sell order that exceeds the current allocation shares
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
      "allocation": 2,
      "shares": 3,
      "type": "sell"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a POST request to "/api/orders" with body:
    """
    {
      "id": 2,
      "portfolio": 1,
      "allocation": 2,
      "shares": 3,
      "type": "sell"
    }
    """
    And the response status code should be 201
    And the response should be empty
    And I send a PATCH request to "/api/orders/1" with body:
    """
    {
      "status": "completed"
    }
    """
    And the response status code should be 200
    And the response should be empty
    When I send a PATCH request to "/api/orders/2" with body:
    """
    {
      "status": "completed"
    }
    """
    Then the response status code should be 409
    And the response should be empty
