package "git"

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


directory node.app.web_dir do
  owner node.user.name
  mode "0755"
  recursive true
end

directory "#{node.app.web_dir}" do
  owner node.user.name
  mode "0755"
  recursive true
end


template "#{node.nginx.dir}/sites-available/#{node.app.name}.conf" do
  source "nginx.conf.erb"
  mode "0644"
end

nginx_site "#{node.app.name}.conf"

cookbook_file "#{node.app.web_dir}/info.php" do
  source "info.php"
  mode 0755
  owner node.user.name
end

php_pear "mongo" do
  action :install
end
