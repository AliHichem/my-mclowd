set :deploy_to, "/var/www/mclowd"
server "mclowd.trisoft.ro", :app, :web, :db, :primary => true