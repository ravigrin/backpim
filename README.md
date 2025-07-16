### Конфигурация

##### Установка зависимостей, создание БД 
1. composer install
2. php bin/console doctrine:database:create
3. php bin/console doctrine:migrations:migrate

##### PIM
1. composer pim-init-data

##### Wildberries
1. php bin/console wb:import catalog
2. php bin/console wb:import attribute
3. php bin/console wb:import product
4. php bin/console wb:import suggest


##### Ozon
1. php bin/console ozon:import catalog
2. php bin/console ozon:import attribute
3. php bin/console ozon:import product
4. php bin/console ozon:import dictionary