# XFramework

1. Before you start work at project, you must create **runtime folder** inside _root_ directory and make a **requests.log** file for logging any errors.

2. Type your terminal
    ```bash 
    composer update
    ```

3. For connecting to database add this code to **config/main.php** file:
    ```php
            'database' => [
                'host' => 'localhost',
                'port' => '3306',
                'engine' => 'mysql',
                'username' => 'db_user',
                'password' => 'db_pass',
                'dbname' => 'xFramework',
                'charset' => 'utf8'
            ]
    ```

    > XFramework had written by Uzbek PHP developers.

4. Please after you create new class or namespace update your __composer.json__