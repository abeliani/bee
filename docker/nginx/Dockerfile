FROM nginx:latest

COPY default.conf /etc/nginx/conf.d/
RUN rm /etc/nginx/conf.d/default.conf

RUN chown -R www-data:www-data /var/www
RUN find /var/www -type d -exec chmod 755 {} \; && find /var/www -type f -exec chmod 644 {} \;

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]