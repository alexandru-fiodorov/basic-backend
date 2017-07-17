Asteroid API
============

### Project requirements
php >= 7.0  
php-mongodb  
mongodb >= 3.2  
phpunit >= 5.7  

### Installation instructions:
`composer install`  
`php bin/console populate`

### Running tests
`phpunit`

Explanations
============

I used symfony in this project because it's the framework I like the most. 
It's very flexible but in the same time it's helping you to keep the code more organized, 
excellent for big projects. 

MongoDB is the DB I am using for the last 2 years and I found it very useful with it's embedded documents.
Also it's fast and much much easier to configure than MySQL server.

I has some doubt about my understanding the requirements for "best month" task.
As I understand I need to return the month like January doesn't matter which year it is?  
For example: 01.2017 has 5 asteroids and 01.2016 has 7 asteroids so we should calculate January with 12 asteroids?  
The solution for this task is too similar with "best year" task's solution
so I thought that maybe I understood it wrong and decided to make also "/best-month-year" route 
where month also depends on year.  
