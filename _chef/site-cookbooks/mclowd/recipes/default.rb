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

nginx_site "#{node.app.name}.conf"

cookbook_file "#{node.app.web_dir}/web/info.php" do
  source "info.php"
  mode 0755
end


