set :deploy_to, "/var/www/mclowd"
set :symfony_env_prod, "prod"
server "mclowd.trisoft.ro", :app, :web, :db, :primary => true