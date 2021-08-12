#!/bin/bash
 cat fixture-dump.sql | docker exec -i 9a29ccf87ab6 mysql -u"sail" -p"password" "laravel_app"
