channels = %w{pear.phpunit.de pear.symfony.com}
channels.each do |chan|
  php_pear_channel chan do
    action [:discover, :update]
  end
end

php_pear "PEAR" do
  cur_version = `pear -V| head -1| awk -F': ' '{print $2}'`
  action :upgrade
  # This feels super ghetto. Open to improvements.
  not_if { Gem::Version.new(cur_version) > Gem::Version.new('1.9.0') }
end

php_pear "PHPUnit" do
  channel "phpunit"
  version "3.7.13"
  action :install
end

bash "install coffee" do
  user "root"
  code "npm install -g coffee-script"
end