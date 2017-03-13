FROM matthiasnoback/php_workshop_tools_base
RUN apt-get -y update && apt-get install -y graphviz
RUN curl -LS http://get.sensiolabs.de/deptrac.phar -o deptrac.phar && chmod +x deptrac.phar && mv deptrac.phar /usr/local/bin/deptrac
