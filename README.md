An educational Phalcon application divided into 2 parts:
- `server`
- `client`

## Requirements

Latest stable [Docker](https://www.docker.com/)

On Windows, the project must be under `C:\Users`

## Getting started
```
git clone https://github.com/beni92/SAM
cd SAM
docker-compose up -d
```

## Stopping
```
docker-compose stop
```

## Removing
```
docker-compose rm
```

## Updating
```
docker-compose pull
```

## Status
```
docker ps

docker-compose ps
```

## Logs
```
docker logs <container id>

docker-compose logs <service name>
```

## Cleanup
```
docker rm -v $(docker ps -a -q -f status=exited)
docker rmi $(docker images -f "dangling=true" -q)
```

## Accessing with Toolbox
```
# mysql
<docker machine ip>:13306

# client
<docker machine ip>:1080

# server
<docker machine ip>:1080
```

The machine IP can be seen when launching Docker Quickstart or using:
```
docker-machine env
```

## Accessing with native Docker
```
# mysql
localhost:13306

# client
localhost:1080

# server
localhost:2080
```

## Accessing from within containers
```
# mysql
mariadb:3306

# client
nginx:1080

# server
nginx:2080
```

## Phalcon CLI
```
cd client
docker run -it --rm $(pwd):/client amqamq/phalcon phalcon
```

## Composer
```
cd client
docker run -it --rm -v $(pwd):/client amqamq/phalcon composer
```

## SASS
```
cd client
docker run -it --rm -v $(pwd):/client amqamq/webtools sass
```

## Exploring container contents
```
docker run -it --rm <volumes> <image> /bin/bash
```

## A quick Debian VM for experiments
```
docker run -it --rm debian:jessie /bin/bash
```

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
