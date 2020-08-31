FROM php:7.4-cli-alpine
COPY ./src /usr/src/myapp
WORKDIR /usr/src/myapp
EXPOSE 8000
CMD [ "php", "-S", "0.0.0.0:8000" ]
