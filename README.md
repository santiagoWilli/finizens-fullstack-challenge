# Finizens' FullStack Challenge

## Make commands

- `make du` will build and run the containers.
- `make ds` will stop the containers.
- `make test` will execute both functional and unit tests.
- `make des` will access the backend container's terminal
- `make def` will access the frontend container's terminal


## Resources

Build and run the Docker containers with `make du` and you'll have available the following:
- http://localhost:8080 - GUI (Vue3 SPA)
- http://localhost:9000 - RESTful API (PHP Slim)
- http://localhost:8081 - phpMyAdmin

## Considerations

You will have to first execute the tests with `make test` in order to set up the user's portfolio.

Due to time limitations, the frontend lacks of any "portfolio creation" option. It also assumes the portfolio with ID 1 always exists and only works with that portfolio.

The RESTful API has been developed following BDD so every decision was taken due to what the Gherkin functional tests expected. I have also added more functional tests that I believe were missing such as those for the `GET /orders` endpoint.

I'm certain both backend and frontend could be refactored and improved. However, I have already spent a lot of time with this technical test and I cannot dedicate more time to it. I would really appreciate any suggestions for improvement.
