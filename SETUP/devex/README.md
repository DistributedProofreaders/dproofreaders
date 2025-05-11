# DP Local development

## docker-based

The docker-based development environment runs MySQL and Apache/PHP in docker
containers and bind mounts the repository. This allows you to make changes to
the code directly on your computer and use a local web browser to interact with
the code.

The MySQL database and the code-generated files are stored in docker volumes.
These will persist across container reboots.

This environment uses the JSON-based development-only "forum" instead of a
full phpBB forum system. This should be adequate for most things that aren't
modifying the phpBB integration.

### Set up

First, install either [Docker Desktop](https://www.docker.com/products/docker-desktop/)
-- free for personal use -- or [Rancher Desktop](https://rancherdesktop.io/).
Note that if you use Rancher Desktop the commands are slightly different,
consult their manual for details.

**Note**: All `docker compose` commands should be run from the base of the checkout.

Start the docker containers:
```bash
docker compose up -d
```

On first run it will download some docker images and build the dpdev image.
When the containers start it will create an initial (empty) database and
provide a web interface on port 8080.

Install the PHP & JS packages:
```bash
docker compose exec dpdev SETUP/install_dependencies.sh
```

Run the configuration script:
```bash
docker compose exec dpdev SETUP/configure SETUP/devex/dpdev-docker/configuration.sh .
```

Initialize the DB _structure_ with:
```bash
docker compose exec dpdev bash -c "cd SETUP && php install_db.php"
```

Now you have a fully-installed and configured DProofreaders repository!
You can access the site locally at http://localhost:8080/c/ and should be
able to register a new user. See the [install guide](../INSTALL.md) for
details on how to make that user an admin.

**Note**: If you want to use the example data (see below) you must load that
before registering new users or the example data will fail to load!

When you're done you can bring down the containers:
```bash
docker compose down
```

**Note**: The docker container runs as root. Docker Desktop on macOS and Windows
appears to automagically manage user permissions. docker on Linux does not and
files and directories it creates like `vendor` and `node_modules` will be owned
by root. To fix these files, run:
```bash
docker compose exec dpdev chown -R $(id -u):$(id -g) .
```

#### Advanced features

The container start-up script can call out to a local (and untracked in git)
script if you want some things to always happen when you start it.

To use a local startup script, create the file
`SETUP/devex/dpdev-docker/container_startup_local.sh`, make it executable, and
put whatever you want to happen inside it. The script will be run from the
base of the code checkout in the container.

It could, for instance, always install the latest NPM and Composer packages for
you:
```bash
#!/bin/bash

composer install
npm install
```

### Loading example data

It's kinda boring though. Let's populate it with some data like users, and a
project to get started.

This is, unfortunately a little fragile and the DB inserts may fail if the DB
structure has changed.
```bash
docker compose exec dpdev SETUP/devex/dpdev-docker/populate_data.sh
```

This creates 3 users with various permissions:
* Site Admin
  * username: `admin`
  * password: `admin`
* Project Manager
  * username: `pm`
  * password: `pm`
* Proofreader
  * username: `proofer`
  * password: `proofer`

A project is also loaded and available for proofreading. The project is managed
by the `pm` user and the `proofer` user has already proofread a page in it.

### Developing

You can update the code directly from the repo and just refresh your browser to
see the changes.

If you want to interact with the database you can do so through the dpdev
container:
```bash
docker compose exec dpdev mysql -hmysql -udp_user -pdp_password dp_db
```

You can also run other standard development commands in the container. Because
the working directory is the base of the checkout, all of the commands are
relative to the base of the check out as well.

For instance, you can run php-cs-fixer and phpstan directly:
```bash
docker compose exec dpdev ./vendor/bin/php-cs-fixer fix
docker compose exec dpdev ./vendor/bin/phpstan
```

As well as the eslint tooling:
```bash
docker compose exec dpdev npm run format-check
docker compose exec dpdev npm run format
```

Or you can just shell into the container to do things too:
```bash
docker compose exec dpdev bash
```

See also the [development](../DEVELOPMENT.md) and [code style](../CODE_STYLE.md)
docs.

### Deleting

```bash
# tear down the containers
docker compose down

# delete the two persistent volumes
docker volume rm devex_dyndir-data
docker volume rm devex_mysql-data
```

## Virtual Machine

To develop within a virtual machine, use the docker-based solution above
within a VM.

In brief:
1. Install Ubuntu 24.04 (or your Linux flavor of choice)
2. Install docker & docker compose:
   ```bash
   sudo apt-get update && sudo apt-get install -y docker.io docker-compose-v2
   ```
3. Add your user to the `docker` group
   ```bash
   sudo usermod -a -G docker $(whoami)
   ```
4. Log out and back in to pick up the new group
5. Clone the repo and follow the instructions above
