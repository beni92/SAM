## SAM Bank

An educational Phalcon application divided into 2 parts:
- `client` (web app)
- `server` (REST API)

## Requirements

Latest stable [Docker](https://www.docker.com/)

On Windows, the project must be under `C:\Users`, eg `C:\Users\abc\PhpstormProjects\SAM`

## Getting started
```
git clone https://github.com/beni92/SAM
cd SAM
docker-compose up -d
```

### Stopping
```
docker-compose stop
```

### Removing
```
docker-compose rm
```

### Updating
```
# code
git pull

# containers
docker-compose pull
```

### Status
```
docker ps

docker-compose ps
```

### Logs
```
docker logs <container id>

docker-compose logs <service name>
```

### Cleanup
```
# remove exited containers
docker rm -v $(docker ps -a -q -f status=exited)

# remove unused images
docker rmi $(docker images -f "dangling=true" -q)
```

## Accessing

### With Toolbox
```
# mysql
<docker machine ip>:13306

# client
http://<docker machine ip>:1080

# server
http://<docker machine ip>:2080
```

The machine IP can be seen when launching Docker Quickstart or using:
```
docker-machine env
```

### With native Docker
```
# mysql
localhost:13306

# client
http://localhost:1080

# server
http://localhost:2080
```

### From within containers
```
# mysql
mariadb:3306

# client
http://nginx:1080

# server
http://nginx:2080
```

## Tools

### Phalcon CLI
```
docker run -it --rm -v $(pwd)/client:/client amqamq/phalcon phalcon
```

### Composer
```
docker run -it --rm -v $(pwd)/client:/client amqamq/phalcon composer
```

### Web tools
```
docker run -it --rm -v $(pwd)/client:/client amqamq/webtools [ruby|sass|node|npm|grunt]
```

### Exploring container contents
```
docker run -it --rm <volumes> <image> /bin/bash
```

### A quick Debian VM for experiments
```
docker run -it --rm debian:jessie /bin/bash
```

## Setting up PhpStorm

1. Clone Phalcon Devtools in your home directory:
    ```
    cd ~
    git clone https://github.com/phalcon/phalcon-devtools
    ```

3. In PhpStorm - File - Settings - Languages & Frameworks - PHP:

    PHP language level: 7

    Include path: `<home dir>\phalcon-devtools\ide\stubs`  
    eg `C:\Users\abc\phalcon-devtools\ide\stubs`

## FAQ

### Docker Quickstart returns errors

Most probably you have just upgraded the Toolbox or VirtualBox. A reboot should solve the problem

### I can't access the web application

- the IP is wrong. Make sure to use the one shown by Docker Quickstart
- the shared folders are broken. Make sure that the mount in VirtualBox machine settings is exactly:
    ```
    /c/Users C:\Users
    ```
    then restart the machine:
    ```
    docker-machine restart
    ```

### `docker-compose` returns `invalid bind mount spec` in PhpStorm on Windows

Workaround: set a user environment variable in Control panel:
```
COMPOSE_CONVERT_WINDOWS_PATHS=1
```

### Which Docker images are we using?

* [library/mariadb](https://hub.docker.com/_/mariadb/)
* [library/nginx](https://hub.docker.com/_/nginx/)
* [amqamq/phalcon](https://hub.docker.com/r/amqamq/phalcon/) (extends [library/php](https://hub.docker.com/_/php/))
* [amqamq/webtools](https://hub.docker.com/r/amqamq/webtools/)

All are based on `debian:jessie`. We will consider switching to [`alpine`](https://alpinelinux.org/) when `mariadb` gets an official support for it
