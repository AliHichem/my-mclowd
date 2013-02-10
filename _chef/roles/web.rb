name "web"
description "Base mclowd web node setup."
 
run_list(
  "recipe[apt]",
  "recipe[mysql::server]",
  "recipe[chef-dotdeb]", 
  "recipe[chef-dotdeb::php54]", 
  "recipe[apache2]", 
  "recipe[apache2::mod_php5]", 
  "recipe[apache2::mod_rewrite]", 
  "recipe[php]",  
  "recipe[beanstalkd]",
  "recipe[mclowd]",
  "recipe[mysql::client]",
  "recipe[mclowd::dev]"
)

default_attributes(
  "app" => {
    "name" => "mclowd",
    "web_dir" => "/var/www/mclowd"
  }
)