## About Project

Mini Aspire
It is realted to loan and Weekly repayment

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
        git clone https://github.com/thakur-deepak/mini-aspire.git
        
    

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
        docker-compose run miniaspire php artisan key:generate

 - **Run database migrations**
    >
        docker-compose run miniaspire php artisan migrate


 - **Run seeder**
    >
        docker-compose run miniaspire php artisan db:seed

 - **Start server**
    >
        docker-compose up --build

As IP is configured in ```docker-compose.yml``` file so API will be running on [http://162.28.1.1](http://162.28.1.1) now.


## Installation


# Services/API Reference

* **Sanctum**
  * Documentation:- https://laravel.com/docs/8.x/sanctum 


# mini-aspire
