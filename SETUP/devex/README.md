# DP Local development

## docker-based

The docker-based development environment runs MySQL and Apache/PHP in docker
containers and bind mounts the repository. This allows you to make changes to
the code directly on your computer and use a local web browser to interact with
the code.

The MySQL database and the code-generated files are stored in docker volumes.
These will persist across container reboots.

### Set up

First, install either [Docker Desktop](https://www.docker.com/products/docker-desktop/)
-- free for personal use -- or [Rancher Desktop](https://rancherdesktop.io/).
Note that if you use Rancher Desktop the commands are slightly different,
consult their manual for details.

_Note: All commands should be run relative to this directory._

Start the docker containers:
```bash
docker compose up -d
```

On first run it will download some docker images and build the dp-dev image.
When the containers start it will create an initial (empty) database and
provide a web interface on port 8080.

Install the PHP & JS packages:
```bash
docker compose exec dp-dev composer install
docker compose exec dp-dev npm install
```

Run the configuration script:
```bash
docker compose exec dp-dev SETUP/configure SETUP/devex/dpdev-docker/configuration.sh .
```

Initialize the DB _structure_ with:
```bash
docker compose exec dp-dev bash -c "cd SETUP && php install_db.php"
```

Finally, initialize some of the "dynamic" directories that `configuration.sh`
points to:
```bash
docker compose exec dp-dev SETUP/devex/dpdev-docker/init_dyndir.sh
```

Now you have a fully-installed and configured DProofreaders repository!
You can access the site locally at http://localhost:8080/c/

When you're done you can bring down the containers:
```bash
docker compose down
```

### Loading example data

It's kinda boring through. Let's populate it with some data like users, and a
project to get started.

This is, unfortunately a little fragile and the DB inserts may fail if the DB
structure has changed.
```bash
docker compose exec dp-dev SETUP/devex/dpdev-docker/populate_data.sh
```

### Developing

You can update the code directly from the repo and just refresh your browser.
If you want to interact with the database you can do so through the dp-dev
container:
```bash
docker compose exec dp-dev mysql -hmysql -udp_user -pdp_password dp_db
```

You can run php-cs-fixer and phpstan directly as well:
```bash
docker compose exec dp-dev ./vendor/bin/php-cs-fixer fix
docker compose exec dp-dev ./vendor/bin/phpstan
```

Or you can just shell into the container to do things too:
```bash
docker compose exec dp-dev bash
```

### Deleting

```bash
# tear down the containers
docker compose down

# delete the two persistent volumes
docker volume rm devex_dyndir-data
docker volume rm devex_mysql-data
```
