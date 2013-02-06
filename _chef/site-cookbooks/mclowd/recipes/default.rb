package "git"
package "acl"
package "php5-mysql"
package "php5-intl"

# config php-fpm for nginx
template "php-fpm.inc" do
  path "#{node[:nginx][:dir]}/conf.d/php-fpm.inc"
  source "php-fpm.config.erb"
  owner "root"
  group "root"
  mode 0644
  action :create
  only_if "dpkg --get-selections | grep php5-fpm"
end

template "#{node.nginx.dir}/sites-available/#{node.app.name}.conf" do
  source "nginx.conf.erb"
  mode "0644"
end

template "/etc/php5/fpm/php.ini" do
  path "/etc/php5/fpm/php.ini"
  source "php.ini"
  owner "root"
  group "root"
  mode 0644
  action :create
end  

template "#{node.app.web_dir}/app/config/parameters.yml" do
  path "#{node.app.web_dir}/app/config/parameters.yml"
  source "parameters.yml"
  mode 0644
  action :create
end  

nginx_site "#{node.app.name}.conf"

cookbook_file "#{node.app.web_dir}/web/info.php" do
  source "info.php"
  mode 0755
end


