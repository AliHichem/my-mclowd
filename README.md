You need virtualbox to run app.

MCLOWD
1) Install Ruby, prefereable via rvm
2) Run (sudo) gem install bundler
3) Run "bundle" command
4) Run cd _chef/ && librarian-chef install
5) vagrant up
6) Navigate to http://192.168.33.10/app_dev.php like a gangster :) (make sure your local port 80 is free)


Notes
When u vagrant ssh to server, app is located at /var/www/mcloud
Its a shared folder to your repo main directory.

QUESTIONS?
Mail me at michal.dabrowski@trisoft.ro 