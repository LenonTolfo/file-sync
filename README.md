This is a simple file sync script to load Json data from a file and save it on the database

# Requirements
 - database (I used mysql on development)
 - [composer](https://getcomposer.org/)
 - [symfony cli](https://symfony.com/download)
 - PHP 7.4

# Installation

Clone project from the repository,
run `composer install`,
set teh doctrine `DATABASE_URL` for your database connection on the `.env file`

# Run

Run the command `symfony console app:datafile:sync` on the console

### Notes
For scheduling the process it is possible to set a cron job to run the same command
