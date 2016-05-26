Vagrant.configure("2") do |config|
    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.hostname = "scotchbox"
    config.vm.synced_folder ".", "/var/www/gsa", :mount_options => ["dmode=775", "fmode=664"]
    config.vm.provision "shell", path: "provision.sh"
end
