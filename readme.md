SMIS | ITC 
==========

[Screenshots](public/img/dashboard.png)


### System requirement

- PHP 7.1
- Postgres 9
- npm > 3.10.8
- node v6.9.1

### Installation

- ```git clone git@bitbucket.org:thavorac/itc-school-management-system.git```
- ```cd project-name```
- ```composer install```
- ```npm install``` (you must to install gulp, Click [here](https://laravel.com/docs/5.1/elixir#installation))
- Create a database and restore existing database

### wkhtmltopdf

You must to install ```wkhtmltopdf``` environment on your local machine, if you avoid to install you won\'t print document on the system. [The full documentation](https://github.com/barryvdh/laravel-snappy/blob/master/readme.md)

### Migration Command

- Edit migrate file 
    ```php
    2016_09_15_165639_modify_bac2_max_name_size.php
    ```
    and rename `\"studentBac2s\"`
- And then run command 
    ```php
    php artisan migrate
    ```
- Re-edit migrate file 
    ```php
    2016_09_15_165639_modify_bac2_max_name_size.php
    ``` 
    
### Generate data into database

- Run db:seed command

    ```php
    php artisan db:seed
    php artisan db:seed --class=SeedCustomsPermissions
    php artisan db:seed --class=GroupTableSeeder
    php artisan db:seed --class=GroupStudentAnnualSeeder
    php artisan db:seed --class=CourseAnnualGroupSeeder
    php artisan db:seed --class=mark_dtc_radie_2017_semester1
    php artisan db:seed --class=AbsenceNotationTableSeeder
    ```
    
- Deploy Timetable
    - Run migrate table
    - Run command Seeder
    
    ```php
    php artisan db:seed --class=GroupStudentAnnualSeeder
    php artisan db:seed --class=TimetableAssignmentSeeder
    php artisan db:seed --class=FakeStudentLanguageGroup
    php artisan db:seed --class=TimetableWeekSeeder
    
    ```
    
- Edit is_vocational is `true` value.
