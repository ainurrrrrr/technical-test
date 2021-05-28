#!/bin/bash

#technical-test-dbo-engineer
printf "composer install on technical-test-dbo-engineer is start\n"
docker exec -it php71 sh -c "cd /usr/share/nginx/html/technical-test-dbo-engineer && composer install"
printf "composer install on technical-test-dbo-engineer is done\n\n\n"

printf "set permission artisan on technical-test-dbo-engineer is start\n"
chmod +x htdocs/technical-test-dbo-engineer/artisan 
printf "set permission artisan on technical-test-dbo-engineer is done\n\n\n"

printf "FINISH ......\n"