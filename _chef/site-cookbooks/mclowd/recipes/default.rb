package "git"
package "acl"
package "php5-mysqlnd"
#package "php5-intl"


template "#{node.app.web_dir}/app/config/parameters.yml" do
  path "#{node.app.web_dir}/app/config/parameters.yml"
  source "parameters.yml"
  mode 0644
  action :create
end  


cookbook_file "#{node.app.web_dir}/web/info.php" do
  source "info.php"
  mode 0755
end

cookbook_file "/etc/php5/apache2/php.ini" do
  path "/etc/php5/apache2/php.ini"
  source "php.ini"
  action :create
end    

web_app "mclowd" do
  server_name 'mclowd.dev'
  docroot  "#{node.app.web_dir}/web"
end