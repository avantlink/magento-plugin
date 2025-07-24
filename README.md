## Setting up the extension for Docker-Magento

- Locate a new folder to be used. 
- in your terminal type `git clone https://github.com/magento/magento-cloud.git <install-directory-name>`
- `cd <install-directory-name>`
- `sudo composer create-project --repository-url=https://magento.com/ magento/project-community-edition`
- `sudo composer require --no-update --dev magento/ece-tools magento/magento-cloud-docker`
- `sudo mkdir .magento.docker.yml`
- `sudo nano .magento.docker.yml`

_Insert the following details into the file and save_
```dockerignore
name: magento
system:
    mode: 'production'
services:
    php:
        version: '8.2'
        extensions:
            enabled:
                - xsl
                - json
                - redis
    mysql:
        version: '10.6'
        image: 'mariadb'
    redis:
        version: '7.0'
        image: 'redis'
    opensearch:
        version: '2.4'
        image: 'magento/magento-cloud-docker-opensearch'
hooks:
    build: |
        set -e
        php ./vendor/bin/ece-tools run scenario/build/generate.xml
        php ./vendor/bin/ece-tools run scenario/build/transfer.xml
    deploy: 'php ./vendor/bin/ece-tools run scenario/deploy.xml'
    post_deploy: 'php ./vendor/bin/ece-tools run scenario/post-deploy.xml'
mounts:
    var:
        path: 'var'
    app-etc:
        path: 'app/etc'
    pub-media:
        path: 'pub/media'
    pub-static:
        path: 'pub/static'
```
- `sudo echo " 127.0.0.1 magento2.docker" | sudo tee -a /etc/hosts`
- `sudo touch auth.json`
- `sudo nano auth.json` OR `sudo vim auth.json`

_Copy the following contents to that file_
```dockerignore
{
    "http-basic": {
        "repo.magento.com": {
            "username": "f934f4147087cfe939398c1513f035af",
            "password": "afb4f31cd98d3e7f416d4566ca514bd6"
        }
    }
}
```

- `sudo composer update`
- `vendor/bin/ece-docker build:compose --mode="developer"`
- `sudo nano docker-compose.yml` OR `sudo vim docker-compose.yml` 
- find `PHP_EXTENSIONS=` and add `ftp` to the end of the extensions (separated by space) then save. 

_At this point the docker environment should be prepared, now we are going to spin up the instance._
- `sudo docker-compose up -d`
_NOTE: If you receive an error about missing extensions during this command you will need to add the mising extensions to the same line we added in `docker-compose.yml`_

- `sudo docker compose run --rm deploy cloud-deploy`
- `sudo docker compose run --rm deploy cloud-post-deploy`

_NOTE: as of the most recent set of updates these two commands may not be necessary_
- `sudo docker compose run --rm deploy magento-command config:set system/full_page_cache/caching_application 2 --lock-env`
- `sudo docker compose run --rm deploy magento-command setup:config:set --http-cache-hosts=varnish`

- `sudo docker compose run --rm deploy magento-command setup:perf:generate-fixtures setup/performance-toolkit/profiles/ce/small.xml`

- `sudo docker compose run --rm deploy magento-command cache:clean`

_NOTE: may need to add "127.0.0.1" to c:\Windows\System32\drivers\etc\hosts when running this on WSL/Windows configurations_

At this point you want to navigate to 'http://magento2.docker/' in your browser to ensure that the local instance is operational

Once everything is operational for the store we want to place our extension in the docker-magento instance
_NOTE: as of right now this is untested but this process does not seem to be working and we may be only able to install from a repository (i.e. our public repo)_

- zip code changes in the `Magento/AvantLink/Tracking` folder using the following command: `zip -r AvantLink_Tracking-1.0.x.zip * -x *.zip` where `x` is the version number in the `composer.json` file 
- copy the zip package to `<magento-install-directory>/magento-docker/project-community-edition/app/code/AvantLink/Tracking`

_Note: you may need to create the directories manually_
- `sudo docker compose run --rm build composer require --no-ansi avantlink/tracking:1.0.x` where `x` is the version number in the composer.json file
- `sudo docker compose run --rm build composer update`
- `sudo docker compose run --rm deploy magento-command setup:upgrade`
- `sudo docker compose run --rm deploy magento-command setup:static-content-content:deploy -f`
- `sudo docker compose run --rm deploy magento-command cache:clean`
- `sudo docker compose run --rm deploy magento-command module:status AvantLink_Tracking`

_NOTE: If you stop the container using `sudo docker compose down -v` and attempt to restart using `sudo docker compose up -d`, you may receive an error saying the default website is not set. 
_if you receive this error, run the following:_
- `sudo rm -rf app/etc/env.php`
- `sudo docker compose up -d`
- `sudo docker compose run --rm deploy cloud-deploy`
- `sudo docker compose run --rm deploy cloud-post-deploy`

Here are the [Official Magento Docs](https://devdocs.magento.com/cloud/docker/docker-installation.html) for building a Docker-Magento environment