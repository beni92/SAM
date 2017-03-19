## SAM Bank

[![Travis](https://img.shields.io/travis/beni92/SAM.svg)](https://travis-ci.org/beni92/SAM) [![Codacy](https://img.shields.io/codacy/grade/f0bc608950d54c70940b970fe8046296.svg)](https://www.codacy.com/app/amq/SAM/dashboard)

An educational Phalcon application divided into 2 parts:
- `client` (web app)
- `server` (REST API)

## Requirements

[docker](https://www.docker.com/) >= 1.10.0

[docker-compose](https://docs.docker.com/compose/) >= 1.6.0

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
docker-compose up -d
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
# exited containers
docker rm -v $(docker ps -f "status=exited" -q -a)

# unused images
docker rmi $(docker images -f "dangling=true" -q)

# unused volumes
docker volume rm $(docker volume ls -f "dangling=true" -q)
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
http://client:80

# server
http://server:80
```

## Tools

### Phalcon CLI
```
docker run -it --rm -v $(pwd)/client:/app amqamq/phalcon phalcon
```

### Composer
```
docker run -it --rm -v $(pwd)/client:/app amqamq/phalcon composer
```

### Web tools
```
docker run -it --rm -v $(pwd)/client:/app amqamq/webtools [ruby|sass|node|npm|grunt|gulp|bower]
```

### Exploring container contents
```
docker run -it --rm -v $(pwd)/client:/app amqamq/phalcon:nginx /bin/bash
```

### Connecting to the same network
```
docker run -it --rm --network=$(docker network ls -f "name=sam_default" -q) debian:jessie /bin/bash
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

    **PHP language level**: 7

    **Include path**: `<home dir>\phalcon-devtools\ide\stubs`  
    eg `C:\Users\abc\phalcon-devtools\ide\stubs`

## Using Xdebug

1. Save the bookmarks for **start debugger** and **stop debugger** from [jetbrains.com/phpstorm/marklets/](https://www.jetbrains.com/phpstorm/marklets/)

2. In PhpStorm in the top right corner, press **Start listening for PHP debug connections**

3. Set some breakpoint

4. Open the page with the breakpoint, click on the **start debugging** bookmark and reload the page

5. PhpStorm window should become active and will ask you to accept an incoming connection, press **Accept**. Now you should see the variables in Debugger

No extra configuration is required. Xdebug expects *any* `remote_host`, *any* `idekey` and `remote_port=9000`

## Changing PHP settings

1. Edit `client/docker/php-dev.ini`, by adding, for example:

    ```
    memory_limit = 256M
    ```

2. Restart `docker-compose`:

    ```
    docker-compose restart
    ```

## Deploying to production

1. Launch a Linux server with any provider

2. Open ports `22, 80, 443, 2376, 2377, 7946, 7946, 4789`

3. Provision Docker from your local machine:

    ```
    docker-machine create --driver=generic --generic-ip-address=1.2.3.4 --generic-ssh-key=key.pem --generic-ssh-user=user sam101
    ```

4. Check the available machines:

    ```
    docker-machine ls
    ```

5. Change you local environmental variables to reflect the remote machine:

    ```
    eval "$(docker-machine env sam101)"
    ```

6. Check what containers are running on the remote machine (should be none):

    ```
    docker ps
    ```

7. Copy `prod.env.example` to `prod.env` and edit it accordingly

8. Deploy

    ```
    docker-compose -f docker-compose-prod.yml up -d
    ```

The deployment includes `v2tec/watchtower` which automatically updates the containers on image pushes (handled by Travis)

## FAQ

### Docker Quickstart returns errors

Most probably you have just upgraded the Toolbox or VirtualBox. A reboot should solve the problem

### `docker-compose` returns `service 'version' doesn't have any configuration options`

Make sure your `docker-compose` version is 1.6.0 or newer:

```
docker-compose --version
```

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

### Web application returns `compileFile ../cache [...] permission denied`

Run on your local machine:

```
chmod 777 client/cache
chmod 777 server/cache
```

### `docker-compose` returns `invalid bind mount spec` in PhpStorm on Windows

Workaround: set a user environment variable in Control panel:
```
COMPOSE_CONVERT_WINDOWS_PATHS=1
```

### Which Docker images are we using?

* [library/mariadb](https://hub.docker.com/_/mariadb/)
* [amqamq/phalcon](https://hub.docker.com/r/amqamq/phalcon/) (based on [library/php](https://hub.docker.com/_/php/) and [library/nginx](https://hub.docker.com/_/nginx/))
* [amqamq/webtools](https://hub.docker.com/r/amqamq/webtools/)

All are based on `debian:jessie`. We will consider switching to [`alpine`](https://alpinelinux.org/) when `mariadb` gets an official support for it
