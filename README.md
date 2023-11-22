# OS2Forms Organisation API

## About the project

Sets up an API with data from [Serviceplatformen Organisation](https://digitaliseringskataloget.dk/integration/sf1500).

### Built with

* [Symfony](https://symfony.com)
* [APi Platform](https://api-platform.com/)

## Getting started

To get a local copy up and running follow these steps.

### Installation

1. Clone the repo

   ```sh
   git clone git@github.com:itk-dev/os2forms_organisation_api.git
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

4. Install yarn packages

   ```sh
   docker compose run --rm node yarn install
   ```

5. Run database migrations

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

### Empty & setup database

```sh
docker compose exec phpfpm bin/console doctrine:database:drop --force
docker compose exec phpfpm bin/console doctrine:database:create
docker compose exec phpfpm bin/console doctrine:migrations:migrate --no-interaction
```

### Fetch data

To fetch data from SF1500 run

```sh
docker compose exec phpfpm bin/console organisation:fetch:data
```

Run the command with the verbose flag if you wish to see progress.

**To avoid issues with memory leaks during development add the**
`--no-debug` **flag to the fetch data command.**

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

Example calls assume the use of `itkdev-docker-compose`.
If you are not using this tool,
replace  `os2forms_organisation_api.local.itkdev.dk`
with the result of `docker compose port nginx 8080`.

#### Search for users

```sh
curl -X 'GET' \
  'https://os2forms_organisation_api.local.itkdev.dk/api/v1/bruger?page=1&navn=Jeppe%20Kuhlmann' \
  -H 'accept: application/ld+json'
```

Search parameters

| Name    | Accepts      | Example                 |
|---------|--------------|-------------------------|
| navn    | Text         | `navn=Jeppe%20Kuhlmann` |
| az      | Text         | `az=az12345`            |
| email   | Text         | `email=jeppe%40test.dk` |
| telefon | Text         | `telefon=12345678`      |
| lokation   | Text         | `lokation=ITK`          |

#### Get info on user

```sh
curl -X 'GET' \
  'https://os2forms_organisation_api.local.itkdev.dk/api/v1/bruger/ffdb7559-2ad3-4662-9fd4-d69849939b66' \
  -H 'accept: application/ld+json'
```

#### Get funktioner

```sh
curl -X 'GET' \
  'https://os2forms_organisation_api.local.itkdev.dk/api/v1/bruger/ffdb7559-2ad3-4662-9fd4-d69849939b66/funktioner' \
  -H 'accept: application/ld+json'
```

### Coding standard tests

The following commands let you test that the code follows the coding standards
we decided to adhere to in this project.

* PHP files (PHP-CS-Fixer with the Symfony ruleset enabled)

   ```sh
   docker compose exec phpfpm vendor/bin/php-cs-fixer fix --dry-run
   ```

* Markdown files (markdownlint standard rules)

  ```sh
  docker compose run --rm node yarn check-coding-standards/markdownlint
  ```
