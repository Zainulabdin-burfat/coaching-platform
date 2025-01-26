# How to start the project

1. Clone the repository into a new directory using the following command:
   ```
   git clone --branch main https://github.com/zainulabdin-burfat/coaching-platform.git
   ```
2. Navigate to the project's root directory:
    ```
    cd coaching-platform
    ```
3. Create a `.env` file by copying the `.env.example` file with the following command:
    ```
    cp .env.example .env
    ```
4. Run Docker Compose to start the containers in the background:
    ```
    docker-compose up -d
    ```
5. Access the application container by running the following command:
    ```
    docker exec -it coaching-platform-app /bin/bash
    ```
6. Once inside the application container, run the following commands to install dependencies, generate keys, and run database migrations and seeders:
    ```
    composer install
    php artisan key:generate
    php artisan storage:link
    php artisan migrate:fresh
    php artisan elastic:migrate:fresh
    php artisan scout:import "App\Models\User"
    php artisan scout:import "App\Models\Coach"
    php artisan scout:import "App\Models\Client"
    php artisan scout:import "App\Models\Session"
    php artisan db:seed
    npm install
    npm run build
    ```
7. To start the Supervisor process manager, run the following commands inside the application container:
    ```
    service supervisor stop
    service supervisor start
    supervisorctl start all
    ```
8. Start the cron job to process the Laravel scheduler in the application container:
    ```
    docker-compose exec -d app cron -f
    ```
9. You can now access the site at `localhost`
