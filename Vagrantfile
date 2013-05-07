# -*- mode: ruby -*-
# vi: set ft=ruby :

#for Vagrant >1.2.x
Vagrant.configure("2") do |config|
    config.vm.provision :shell, :inline => "echo Welcome to Mclowd VM"

    config.vm.define :dev do |_config|
      _config.vm.box = "mclowd_deploy"

      #use :nfs => true only if on mac/linux; use :nfs => false on win
      _config.vm.synced_folder ".", "/vagrant/mclowd", :nfs => true

      #_config.vm.provider :vmware_fusion do |vf, override|
      #  vf.customize ["modifyvm", :id, "--memory", 1024]
      #  vf.gui = false
      #  override.vm.box_url = "http://files.vagrantup.com/precise64_vmware_fusion.box"
      #  override.vm.network :private_network, ip: "172.16.169.131"
      #end

      _config.vm.provider :virtualbox do |vb, override|
        vb.customize ["modifyvm", :id, "--memory", 1024]
        override.vm.box_url = "http://www.trisoft.ro/mclowd_deploy.box"
        override.vm.network :private_network, ip: "192.168.33.300"
      end
    end

end
