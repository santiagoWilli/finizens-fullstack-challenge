install: 
	echo "Installing mock server for frontend development purposes..."
	cd frontend_server && npm install;
	echo "Finished, run 'make start' for starting the mocked server"

start:
	echo "Launching mocked server, visit http://localhost:3000"
	cd frontend_server && npm start

du:
	docker compose up --build -d;
ds:
	docker compose down --rmi local;
de:
	docker-compose exec server bash
ps:
	docker-compose ps
