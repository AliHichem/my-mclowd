#!/bin/bash -x
app/console doctrine:database:drop --env=test --force
app/console doctrine:database:create --env=test
app/console doctrine:schema:create --env=test
bin/behat