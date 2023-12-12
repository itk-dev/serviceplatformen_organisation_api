# Serviceplatformen Organisation API

## About the project

Sets up an API with data from [Serviceplatformen Organisation](https://digitaliseringskataloget.dk/integration/sf1500).

### Built with

* [Symfony](https://symfony.com)
* [API Platform](https://api-platform.com/)

## SF1500 Data

The objects from SF1500 that we are interested in are
`Person`, `Bruger`, `Adresse`, `OrganisationFunktion` and `OrganisationEnhed`.
Each of these come with an identifier and at least one `Registrering`.
See [SF1500 objects](docs/class_information_model_organisation.png)
and [object model](docs/object_model_organisation.png).

We create views combining information from objects,
resulting in the following views which will serve as the base for our API.

| View         | Columns                                                                                |
|--------------|----------------------------------------------------------------------------------------|
| bruger       | id, az, navn, email, telefon, lokation                                                 |
| funktion     | id, bruger_id, funktionsnavn, enhedsnavn, adresse, tilknytted_enhed_id, funktions_type |
| organisation | id, enhedsnavn, overordnet_id                                                          |

## Getting started

To get a local copy up and running follow these steps.

### Installation

1. Clone the repo

   ```sh
   git clone git@github.com:itk-dev/serviceplatformen_organisation_api.git
   ```

2. Pull docker images and start docker containers

   ```sh
   docker compose pull
   docker compose up --detach
   ```

3. Install composer packages

   ```sh
   docker compose exec phpfpm composer install
   ```

4. Run database migrations

   ```sh
   docker compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
   ```

You should now be able to browse to the api

```sh
open "http://$(docker compose port nginx 8080)/api"
```

### Configuration

Configure the following environment variables in `.env.local`

```sh
###> serviceplatformen ###
# Certificate options
SF1500_ORGANISATION_CERTIFICATE_LOCATOR_TYPE=APP_SF1500_ORGANISATION_CERTIFICATE_LOCATOR_TYPE
# LOCATOR_TYPE Should be 'azure_key_vault' or 'file_system'.
SF1500_ORGANISATION_CERTIFICATE_PATH=APP_SF1500_ORGANISATION_CERTIFICATE_PATH
SF1500_ORGANISATION_CERTIFICATE_TENANT_ID=APP_SF1500_ORGANISATION_CERTIFICATE_TENANT_ID
SF1500_ORGANISATION_CERTIFICATE_APPLICATION_ID=APP_SF1500_ORGANISATION_CERTIFICATE_APPLICATION_ID
SF1500_ORGANISATION_CERTIFICATE_CLIENT_SECRET=APP_SF1500_ORGANISATION_CERTIFICATE_CLIENT_SECRET
SF1500_ORGANISATION_CERTIFICATE_NAME=APP_SF1500_ORGANISATION_CERTIFICATE_NAME
SF1500_ORGANISATION_CERTIFICATE_SECRET=APP_SF1500_ORGANISATION_CERTIFICATE_SECRET
SF1500_ORGANISATION_CERTIFICATE_VERSION=APP_SF1500_ORGANISATION_CERTIFICATE_VERSION
SF1500_ORGANISATION_CERTIFICATE_PASSPHRASE=APP_SF1500_ORGANISATION_CERTIFICATE_PASSPHRASE
# Other options
SF1500_ORGANISATION_AUTHORITY_CVR=APP_SF1500_ORGANISATION_AUTHORITY_CVR
SF1500_ORGANISATION_STS_APPLIES_TO=APP_SF1500_ORGANISATION_STS_APPLIES_TO
SF1500_ORGANISATION_TEST_MODE=APP_SF1500_ORGANISATION_TEST_MODE
SF1500_ORGANISATION_MANAGER_ROLE_UUID_TEST=APP_SF1500_ORGANISATION_MANAGER_ROLE_UUID_TEST
SF1500_ORGANISATION_MANAGER_ROLE_UUID_PROD=APP_SF1500_ORGANISATION_MANAGER_ROLE_UUID_PROD
###< serviceplatformen ###
```

## Commands

Before fetching data the database should be emptied.
If not, objects that were available during both fetches will appear twice.

### Drop & setup database

```sh
docker compose exec phpfpm bin/console doctrine:database:drop --force
docker compose exec phpfpm bin/console doctrine:database:create
docker compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
```

### Fetch data

To fetch data from SF1500 run

```sh
docker compose exec phpfpm bin/console organisation:fetch:data DATATYPES --page-size=PAGE-SIZE --max=MAX
```

for help run

```sh
docker compose exec phpfpm bin/console organisation:fetch:data --help
```

**To avoid issues with memory leaks during development add the
`--no-debug` flag to the fetch data command.** You may also want to
add the verbose flag to see progress.

```sh
docker compose exec phpfpm bin/console --no-debug organisation:fetch:data -vvv
```

## API

See

```sh
open "http://$(docker compose port nginx 8080)/api"
```

for api specifics.

### Example calls

#### Search for users

```sh
curl "http://$(docker compose port nginx 8080)/api/v1/bruger?page=1&navn=Jeppe%20Kuhlmann"
```

Search parameters

| Name    | Type | Example                 |
|---------|------|-------------------------|
| navn    | Text | `navn=Jeppe%20Kuhlmann` |
| az      | Text | `az=az12345`            |
| email   | Text | `email=jeppe%40test.dk` |
| telefon | Text | `telefon=12345678`      |
| lokation   | Text | `lokation=ITK`          |

#### Get info on user

```sh
curl "http://$(docker compose port nginx 8080)/api/v1/bruger/ffdb7559-2ad3-4662-9fd4-d69849939b66"
```

Here `ffdb7559-2ad3-4662-9fd4-d69849939b66` should be a `bruger` identifier.

#### Get funktioner

```sh
curl "http://$(docker compose port nginx 8080)/api/v1/bruger/ffdb7559-2ad3-4662-9fd4-d69849939b66/funktioner"
```

Here `ffdb7559-2ad3-4662-9fd4-d69849939b66` should be a `bruger` identifier.

### Using the API from another docker compose setup

To use the API you must use the `serviceplatformen_organisation_api_app`
network.

```sh
networks:
  organisation_api:
    external: true
    name: serviceplatformen_organisation_api_app

services:
  phpfpm:
    networks:
      - organisation_api
    ...
```

The `nginx` container has been overridden to have the container name
`organisation_api`. Calls from other projects already having a `nginx`
container should use `organisation_api` to make calls to the API, i.e.

```sh
curl "http://organisation_api:8080/api/v1/bruger?page=1&navn=Jeppe%20Kuhlmann"
```

### Coding standard tests

The following commands let you test that the code follows the coding standards
we decided to adhere to in this project.

* PHP files (PHP-CS-Fixer with the Symfony ruleset enabled)

   ```sh
   docker compose exec phpfpm composer coding-standards-check
   ```

* Markdown files (markdownlint standard rules)

  ```sh
  docker compose run --rm node yarn coding-standards-check/markdownlint
  ```

### Code analysis

psalm

```sh
docker compose exec phpfpm composer code-analysis
```
