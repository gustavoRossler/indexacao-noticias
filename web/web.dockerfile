FROM nginx:1.10

ADD scripts/vhost.conf /etc/nginx/conf.d/default.conf