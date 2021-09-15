docker-compose up -d
docker exec php bash -c 'composer install'
echo 'We need to give the database some overhead time to fully initialize. Please wait 2 minutes'
sleep 2m
cd www/
composer exec-migrations
composer test