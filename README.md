# Shroomer

- [Docker](https://www.docker.com/)
- [Symfony](https://symfony.com)
- [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) 


## Getting Started

The project is:
- running with docker and docker compose, you must have it installed,
- manageable with makefile, you must have the make command available,

Build and start the application server with:
```shell
make start 
```

To get a bash on php server:
```shell
make bash
```

Start the front end server (dev):
```shell
make node-start-dev
```

The front end is available at [localhost:5173](http://localhost:5173/)

## DayToDay usage

```
docker run -it --rm jakzal/phpqa
phpqa phpstan analyse src
phpqa phpcs src
phpqa phpcbf src
```

## ASCII arts

Most of ascii art are generated using pyBonsai:
```
pybonsai --width 84 --height 40 -C zZ -l 8 -L 8 -c '|' -S 18 -f -a 330  
pybonsai --width 84 --height 40 -C zZ -l 8 -L 8 -c '|' -S 18 -f -a 30  
```

