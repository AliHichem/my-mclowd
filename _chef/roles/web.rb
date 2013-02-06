name "web"
description "Base mclowd web node setup."
 
run_list(
  "recipe[mclowd::apt]",
  "recipe[nginx::source]",
  "recipe[php]",
  "recipe[php-fpm]",
  "recipe[beanstalkd]",
  "recipe[mysql::client]",
  "recipe[mysql::server]",
  "recipe[mclowd]"
)

default_attributes(
  "app" => {
    "name" => "mclowd",
    "web_dir" => "/var/www/mclowd"
  },
  "php" => {
    "version" => "5.3.10",
    "install_method" => "package"
  },
  "nginx" => {
    "version" => "1.2.6",
    "default_site_enabled" => true,
    "source" => {
        "modules" => ["http_gzip_static_module", "http_ssl_module"]
    }
  }
)