## About Project

This is the space where you can add the description of your project, once you have cloned the boilerplate and you have replaced the project path from ucreate-laravel_boilerplate to your project name.

## Technologies

- Language: PHP
- Framework: Laravel
- Database: PostgreSql


#### Basic Requirements
  * Git  [Install git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
  * Docker [Install docker](https://docs.docker.com/engine/install)
  * Docker-Compose [Install docker-compose](https://docs.docker.com/compose/install)

## How to set up the dev environment
  
   Open CLI and run following commands to set up at local:
   
  - **Clone the project**
       >
        git clone https://github.com/uCreateit/ucreate-laravel_boilerplate.git
        
    The above command is to clone the boilerplate structure and set up. Once done, replace ucreate-laravel_boilerplate to your project name, so the project repository path will become something like https://github.com/uCreateit/myProject.git (feel free to update this guideline to your project path, once the boilerplate work has been done)

  - **Go to project directory**
       >
        cd xxx
        cp .env.example .env

  - **Run migrations within the container (create tables)**
       >
        docker-compose run web composer install

# Post Installation steps

**Note**: Before runnning below please make sure to add config variables to .env file . Please check syntax [here](https://docs.docker.com/compose/env-file/#syntax-rules)

 - **Generate key**
    >
        docker-compose run web php artisan key:generate

 - **Run database migrations**
    >
        docker-compose run web php artisan migrate


 - **Run seeder**
    >
        docker-compose run web php artisan db:seed

 - **Start server**
    >
        docker-compose up --build

As IP is configured in ```docker-compose.yml``` file so API will be running on [http://172.28.1.1](http://172.28.1.1) now.


## Installation


# External Services/API Reference

* **PostMark**
  * Postmark is used for Email Service.
  * Create Account on [Postmark](https://postmarkapp.com) and verify the sender signatures.
  * Create new server (if required) and Get server API token from 'Credentials' tab under the created server.
  * Set 'Server API token' as Postmark username and password in environment/config variables

* **AWS S3**
  * AWS S3 is used for uploading/storing files including images.
  * Your S3 credentials can be found on the Security Credentials section of AWS Account
  * To create a bucket access the S3 section of the AWS Management Console
  * Set AWS access key, secret key, bucket name etc. as environment variables.
  * Reference: https://aws.amazon.com/s3
# mini-aspire
