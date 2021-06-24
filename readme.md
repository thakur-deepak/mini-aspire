# Laravel Boilerplate

## About this repo

This boilerplate use CircleCI as continous integration and deploy laravel7 application on heroku

### Basic Requirements
  * Git
  * Apache2
  * PHP 7.4
  * Composer
  

### Installation

```bash
git clone git@github.com:uCreateit/ucreate-laravel_boilerplate.git project_name
cd project_name
rm -rf .git
git init
git add .
git commit -m 'Initial boilerplate commit'
git remote add origin git@github.com:uCreateit/project_name.git
git push -u origin master
```


#### Composer packages

* [Laravel](https://laravel.com)
* [Composer Git Hooks](https://github.com/BrainMaestro/composer-git-hooks)
* [Parallel Lint](https://github.com/JakubOnderka/PHP-Parallel-Lint)
* [SensioLabs Security Checker](https://github.com/sensiolabs/security-checker)
* [PHP_Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHP Mess Detector](https://github.com/phpmd/phpmd)

#### Additional configs and shell scripts

* [.circleci](.circleci) /    
  * [config.yml](.circleci/config.yml) - CircleCI config file
  * [schemacrawler.sh](.circleci/schemacrawler.sh) - DB schema ER diagram generation and upload into Review Tool    
* [.deploy](.deploy) /  
  * [commands](.deploy/commands) /    
    * [parallel_lint.sh](.deploy/commands/parallel_lint.sh) - PHP Parallel Lint exec script  
    * [phpcs.sh](.deploy/commands/phpcs.sh) - PHP CodeSniffer exec script
    * [phpmd.sh](.deploy/commands/phpmd.sh) - PHP Mess Detector exec script
    * [phpunit.sh](.deploy/commands/phpunit.sh) - PHPUnit exec script
    * [security_checker.sh](.deploy/commands/security_checker.sh) - SensioLabs Security Checker exec script
    * [newman.sh](.deploy/commands/newman.sh) - Run newman Test cases
  * [phpcs_ruleset.xml](.deploy/phpcs_ruleset.xml) - ruleset for PHP_CodeSniffer
  * [phpmd_ruleset.xml](.deploy/phpmd_ruleset.xml) - ruleset for PHP Mess Detector
  * [pre-commit.sh](.deploy/pre_commit.sh) - git pre-commit hook exec script
  * [pre-push.sh](.deploy/pre_push.sh) - git pre-push hook exec script


### Include config for
- Newrelic (heroku)
- Rollbar
- CORS  
- Repositories
- Newman (for postman test cases)
