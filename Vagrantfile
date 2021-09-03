# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.boot_timeout = 1200

  config.vm.box = "ubuntu/xenial64"

  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    webserver.vm.network "private_network", ip: "192.168.2.11"

    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql

      cp /vagrant/website.conf /etc/apache2/sites-available/

      a2ensite website
      a2dissite 000-default
      service apache2 reload

    SHELL
  end

  config.vm.define "dbserver" do |dbserver|
    dbserver.vm.hostname = "dbserver"
    dbserver.vm.network "private_network", ip: "192.168.2.12"

    dbserver.vm.provision "shell", inline: <<-SHELL

      apt-get update

      export MYSQL_PWD='insecure_mysqlroot_pw'
      echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections
      echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections
      apt-get -y install mysql-server
      echo "CREATE DATABASE fvision;" | mysql
      echo "CREATE USER 'webuser'@'%' IDENTIFIED BY 'insecure_db_pw';" | mysql
      echo "GRANT ALL PRIVILEGES ON fvision.* TO 'webuser'@'%'" | mysql

      export MYSQL_PWD='insecure_db_pw'
      cat /vagrant/database.sql | mysql -u webuser fvision

      sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf
      service mysql restart

    SHELL
  end

  config.vm.define "reportserver" do |reportserver|
    reportserver.vm.hostname = "reportserver"
    reportserver.vm.network "private_network", ip: "192.168.2.13"

    reportserver.vm.provision "shell", inline: <<-SHELL

      apt-get update

      apt-get install -y apache2 php libapache2-mod-php php-mysql

      cp /vagrant/website.conf /etc/apache2/sites-available/

      a2ensite website
      a2dissite 000-default
      service apache2 reload

    SHELL
  end
end
