# Capifony documentation: http://capifony.org
# Capistrano documentation: https://github.com/capistrano/capistrano/wiki

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
set :php_bin, '/usr/bin/php'
set :stages, %w(staging production)
set :stage_dir, 'app/config/deploy' # needed for Symfony2 only
set :default_stage, "staging"
require 'capistrano/ext/multistage'

set :application, "mclowd"
set :app_path,    "app"
set :user,        "root"
set :scm,         :git
set :repository,  "git@github.com:tsslabs/mclowd-marketplace.git"
set :branch,      "master"
set :deploy_via,  :remote_cache

ssh_options[:forward_agent] = true
default_run_options[:pty] = true

set :use_composer,   true
set :update_vendors, false

set :writable_dirs,     ["app/cache", "app/logs"]
set :webserver_user,    "www-data"
set :permission_method, :acl

set :shared_files,    ["app/config/parameters.yml", "web/.htaccess", "web/robots.txt"]
set :shared_children, ["app/logs", "app/spool", "vendor"]


set :model_manager, "doctrine"

set :use_sudo,    false

set :keep_releases, 3

before 'symfony:composer:install', 'symfony:copy_vendors'

namespace :symfony do
  desc "Copy vendors from previous release"
  task :copy_vendors, :except => { :no_release => true } do
    if Capistrano::CLI.ui.agree("Do you want to copy last release vendor dir then do composer install ?: (y/N)")
      capifony_pretty_print "--> Copying vendors from previous release"

      run "cp -a #{previous_release}/vendor #{latest_release}/"
      capifony_puts_ok
    end
  end

  namespace :composer do
    
    desc "Runs composer to install vendors from composer.lock file"
    task :install, :roles => :app, :except => { :no_release => true } do
      capifony_pretty_print "--> Installing Composer dependencies"
      run "cd #{latest_release} && composer install"
      capifony_puts_ok
    end

    desc "Runs composer to update vendors, and composer.lock file"
    task :update, :roles => :app, :except => { :no_release => true } do
      capifony_pretty_print "--> Updating Composer dependencies"
      run "cd #{latest_release} && composer update"
      capifony_puts_ok
    end
  end

end

after "symfony:assets:install", "symfony:doctrine:schema:update"
after "deploy:update", "deploy:cleanup"
after "deploy", "deploy:set_permissions"
