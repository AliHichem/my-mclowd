# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'rubygems'
require 'bundler'

Bundler.require
require 'multi_json'

Vagrant::Config.run do |config|
  
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  config.vbguest.auto_update = false
  config.vbguest.no_remote = true
 
  # Assign this VM to a host-only network IP, allowing you to access it
  # via the IP. Host-only networks can talk to the host machine as well as
  # any other machines on the same network, but cannot be accessed (through this
  # network interface) by any external networks.
  # config.vm.network :hostonly, "192.168.33.10"

  # Assign this VM to a bridged network, allowing you to connect directly to a
  # network using the host's network device. This makes the VM appear as another
  # physical device on your network.
  # config.vm.network :bridged

  # Forward a port from the guest to the host, which allows for outside
  # computers to access the VM, whereas host only networking does not.
  config.vm.forward_port 80, 1818

  # Share an additional folder to the guest VM. The first argument is
  # an identifier, the second is the path on the guest to mount the
  # folder, and the third is the path on the host to the actual folder.

  config.vm.share_folder "v-mclowd", "/var/www/mclowd", ".", :owner => "www-data", :group => "www-data", :create => true, :nfs => true

  VAGRANT_JSON = MultiJson.load(Pathname(__FILE__).dirname.join('_chef', 'nodes', 'vagrant.json').read)

  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path =  ["_chef/site-cookbooks", "_chef/cookbooks"]
    chef.roles_path = "_chef/roles"
    chef.data_bags_path = "_chef/data_bags"
    chef.provisioning_path = "/tmp/vagrant-chef"
   
    chef.json = VAGRANT_JSON
    chef.add_recipe "sudo"
    VAGRANT_JSON['run_list'].each do |recipe|
      chef.add_recipe(recipe)
    end if VAGRANT_JSON['run_list']

    Dir["#{Pathname(__FILE__).dirname.join('_chef', 'roles')}/*.json"].each do |role|
      chef.add_role(role)
    end

  end

  
  
end
