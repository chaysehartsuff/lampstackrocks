
## Deployment

* To run the application run ./scripts/start.sh
* To close the application run ./scripts/stop.sh
* NOTE: MySQL container may take a minute to start

## Structure

* Uses a Docker Compose setup with a 'db' and 'web' container
* The 'web' container includes PHP and Apache to have "PHP be configured as a module of apache."
* ./src contains all the PHP and SQL for the application
* ./logs is mounted with apache logs
* .env defines the mysql credentials

## Application
* Served at http://localhost:8080 after started
* Basic CRUD application allowing add, edit and deleting of users
* Fake user data seeded during deployment
* index.php is the users page, you can search or **click** on a row to edit
* Search and Refresh features on the user table use ajax calls