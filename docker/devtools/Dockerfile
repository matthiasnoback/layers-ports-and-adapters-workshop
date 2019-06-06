FROM matthiasnoback/php_workshop_tools_base
RUN printf "deb http://archive.debian.org/debian/ jessie main\ndeb-src http://archive.debian.org/debian/ jessie main\ndeb http://security.debian.org jessie/updates main\ndeb-src http://security.debian.org jessie/updates main" > /etc/apt/sources.list
RUN apt-get -y update && apt-get install -y graphviz
RUN curl -LS http://get.sensiolabs.de/deptrac.phar -o deptrac.phar && chmod +x deptrac.phar && mv deptrac.phar /usr/local/bin/deptrac
