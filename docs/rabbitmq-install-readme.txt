#Tested on Fedora 32 Server
#install erlang
sudo dnf -y install erlang

#create a new repository file
sudo vi /etc/yum.repos.d/rabbitmq_rabbitmq-server.repo

#paste the following data in the file, we created above
#repo file starts here
[rabbitmq_rabbitmq-server]
name=rabbitmq_rabbitmq-server
baseurl=https://packagecloud.io/rabbitmq/rabbitmq-server/el/7/$basearch
repo_gpgcheck=1
gpgcheck=0
enabled=1
gpgkey=https://packagecloud.io/rabbitmq/rabbitmq-server/gpgkey
sslverify=1
sslcacert=/etc/pki/tls/certs/ca-bundle.crt
metadata_expire=300

[rabbitmq_rabbitmq-server-source]
name=rabbitmq_rabbitmq-server-source
baseurl=https://packagecloud.io/rabbitmq/rabbitmq-server/el/7/SRPMS
repo_gpgcheck=1
gpgcheck=0
enabled=1
gpgkey=https://packagecloud.io/rabbitmq/rabbitmq-server/gpgkey
sslverify=1
sslcacert=/etc/pki/tls/certs/ca-bundle.crt
metadata_expire=300
#repo file ends here

#save the file
#Now, acutal rabbitmq installation
sudo dnf makecache -y --disablerepo='*' --enablerepo='rabbitmq_rabbitmq-server'
sudo dnf -y install rabbitmq-server

#check if rabbitmq is installed properly
rpm -qi rabbitmq-server

#start rabbitmq service
sudo systemctl start rabbitmq-server
sudo systemctl enable rabbitmq-server

#optional, this will enable rabbitmq web dashboard
#if this command runs successfully, we can confirm that rabbitmq is working properly
sudo rabbitmq-plugins enable rabbitmq_management
