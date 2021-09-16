---
to: docker-compose.yml
---
version: "3.8"
services:
    db:
        image: mysql:5.7
        container_name: local_db_container
        volumes:
            - "./.data/db:/var/lib/mysql2"
        ports:
            - 3306:3306
        environment:
            MYSQL_ROOT_PASSWORD: wordpress
            MYSQL_DATABASE: wordpress
            MYSQL_USER: wordpress
            MYSQL_PASSWORD: wordpress

    wordpress:
        depends_on:
            - db
        build: "./wordpress"
        container_name: local_wp_container
        volumes:
            - "./mytheme:/var/www/html/wp-content/themes/mytheme"
            - "./plugins/advanced-custom-fields-pro:/var/www/html/wp-content/plugins/advanced-custom-fields-pro"
        links:
            - db
        ports:
            - 8888:80
        environment:
            WORDPRESS_DB_HOST: db:3306
            WORDPRESS_DB_USER: wordpress
            WORDPRESS_DB_PASSWORD: wordpress
            WORDPRESS_DB_NAME: wordpress

    mailhog:
        image: mailhog/mailhog
        ports:
            - "8025:8025"
            - "1025:1025"
#  phpmyadmin:
#    image: phpmyadmin/phpmyadmin
#    environment:
#      - PMA_ARBITRARY=1
#      - PMA_HOST=db_recruitment
#      - PMA_USER=root
#      - PMA_PASSWORD=password
#    links:
#      - db_recruitment
#    ports:
#      - '8080:80'
#    volumes:
#      - "./.data/sessions:/session"
#    depends_on:
#      - db_recruitment