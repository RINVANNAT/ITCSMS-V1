SMIS | ITC [Screenshots](http://elearning.itc.edu.kh:1000/elearning/wp-content/uploads/2016/07/ITC_logo.jpg)
====================================================

[![Latest Stable Version](https://poser.pugx.org/rappasoft/laravel-5-boilerplate/v/stable)](https://packagist.org/packages/rappasoft/laravel-5-boilerplate) [![Latest Unstable Version](https://poser.pugx.org/rappasoft/laravel-5-boilerplate/v/unstable)](https://packagist.org/packages/rappasoft/laravel-5-boilerplate)

### Laravel 5.1

You can download the last stable build of Laravel 5.1 [here](https://github.com/rappasoft/laravel-5-boilerplate/tree/Legacy_5.1)

### Introduction

Laravel Boilerplate provides you with a massive head start on any size web application. It comes with a full featured access control system out of the box with an easy to learn API and is built on a Twitter Bootstrap foundation with a front and backend architecture. We have put a lot of work into it and we hope it serves you well and saves you time!

### Wiki

Please view the [wiki](https://github.com/rappasoft/laravel-5-boilerplate/wiki) for a list of [features](https://github.com/rappasoft/laravel-5-boilerplate/wiki#features) as well as [installation instructions](https://github.com/rappasoft/laravel-5-boilerplate/wiki/1.-Installation)

### Issues

If you come across any issues please [report them here](https://github.com/rappasoft/Laravel-5-Boilerplate/issues).

### Contributing

Thank you for considering contributing to the Laravel Boilerplate project! Please feel free to make any pull requests, or e-mail me a feature request you would like to see in the future to Anthony Rappa at rappa819@gmail.com.

### Security Vulnerabilities

If you discover a security vulnerability within this boilerplate, please send an e-mail to Anthony Rappa at rappa819@gmail.com, or create a pull request if possible. All security vulnerabilities will be promptly addressed. Please reference [this page](https://github.com/rappasoft/laravel-5-boilerplate/wiki/7.-Security-Fixes) to make sure you are up to date.

### Donations

If you would like to help the continued efforts of this project, any size [donations](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=JJWUZ4E9S9SFG&lc=US&item_name=Laravel%205%20Boilerplate&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted) are welcomed and highly appreciated.

### License

The Laravel framework is open-sourced software licensed under the MIT license

### Migration Command

- Edit migrate file 
    ```
    2016_09_15_165639_modify_bac2_max_name_size.php
    ```
    and rename `\"studentBac2s\"`
- And then run command 
    ```
    php artisan migrate
    ```
- Re-edit migrate file 
    ```
    2016_09_15_165639_modify_bac2_max_name_size.php
    ``` 
    
### Generate data into database
- Run db:seed command

    ```
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
    
    ```
    php artisan db:seed --class=GroupStudentAnnualSeeder
    php artisan db:seed --class=TimetableAssignmentSeeder
    php artisan db:seed --class=FakeStudentLanguageGroup
    php artisan db:seed --class=TimetableWeekSeeder
    
    ```
    
- Edit is_vocational is `true` value.
    