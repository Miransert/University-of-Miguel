# Assignment 2: University of Miguel

In this assignment, we will create a minor LMS (Learning Management System) using the Laravel MVC framework.

You must follow the instructions **to the letter**.
If you do not follow the instructions correctly, you may have a functional code that may not pass the tests.
If something like that happens, let us know to analyze your particular case and determine if we need to fix our tests.

For this assignment, you will develop a system where users can manage courses that belong to faculties.

<span style="color:red">**Disclaimer:** Unless otherwise instructed, do not in any way, modify the contents of the `/tests` directory or the `.gitlab-ci.yml` file. Doing so will be considered cheating, and will in best case result in your assignment being failed.</span>

## Setup

1. Clone your project locally.
2. Run `composer install` to install php dependencies.
3. Create a copy of the .env.example file named .env. This can be done with the command `cp .env.example .env`
4. Run `php artisan key:generate` to generate a random encryption key for your application
5. Run `php artisan serve` to boot up your application


### The database
The project requires a connection to a database. Thanks to docker, this is extremely simple and platform agnostic. To spin up a MySQL server, simply run the `docker-compose up -d` within the directory. This will pull a MySQL server, port-forward it to port 3306 on your machine, and start it in detached mode.

Additionally we have included an installation of _phpmyadmin_ that you can use to explore the database (this will start as part of the docker command), simply go to [http://localhost:8036](http://localhost:8036) and you should see something like this:

![](https://codimd.s3.shivering-isles.com/demo/uploads/upload_167959c79a2bdfdf204221075b524b59.png)
(if the database is empty, you haven't migrated it yet)

You are of course still free to use whichever tool you prefer.

The connection to the database is defined as follows:
- host: `localhost`
- port: `3306`
- username: `root`
- password: `secret`
- database: `webtech`

If you followed the steps mentioned earlier and copied your `.env.example` to `.env`, then Laravel should already be configured with the correct connection details.

_Hint: your JetBrains Student subscription comes bundled with __DataGrip__, which can be used to explore your database._

### Relevant commands

- `php artisan migrate` - This will synchronize your database structure to your migrations (read more [here](https://laravel.com/docs/migrations#introduction)), these can be viewed under `database/migrations`. Laravel comes bundled with some by default, which you can either ignore or delete.
- `php artisan migrate:fresh` - Deletes everything within your database and starts the migrations from scratch, very useful during development.
- `php artisan make:controller {name of Controller}` - This creates a controller with a specified name. Controllers in Laravel use a singular noun with the `Controller` suffix (HomeController, UserControler... e.g.)
- `php artisan make:model {name of model}` - Creates a model with a specified name (usually singular (User, House, Apartment, Animal...))
- `php artisan make:model {name of model} -mc` - Allows us to create a model with a given name, as well as a controller for it and a migration.
- `php artisan serve` - Starts the development server for the application.
- `php artisan db:seed` - Seeds your database with the five faculties.

### Assessing your solution

Every time you push your code to our source control (gitlab.sdu.dk) (which you will have to do to pass), your code will be validated to see if it meets the requirements of this assignment. This is how we assess your solution.

Nevertheless, you are expected to run your tests locally before pushing your code. This is particularly important because testing the solution only in our system can be slow, especially if other people are also doing it simultaneously (then you will most likely be put in a queue).


#### Running browser tests

You should run the browser tests using Laravel Dusk.

The first time you run the tests on your machine, you will have to install the latest `Chrome` binaries; this can be done with the `php artisan dusk:chrome-driver` command (make sure you have the latest version of chrome).

In another terminal, run `php artisan serve` - this is needed as dusk actively uses the server to test your implementation. Make sure the server is up and running every time you test your implementation.

In your main terminal, run: `php artisan dusk --browse` - this will start running your tests.

##### Running individual browser tests

It's also possible to run a single test instead of all of them at once. This is beneficial if you just want to focus on one task before proceeding to the next.

The project contains 16 tests that you can run individually:

1. `php artisan dusk --filter testTask1CreateCourse`: Tests that the form can be filled correctly
2. `php artisan dusk --filter testTask1NotUsingStaticContent`: Tests that you are not simply hardcoding the faculties, but actually retrieving them from the database
3. `php artisan dusk --filter testTask2StoreCourseValidInput`: Tests that the form can be submitted and that the user is redirected to the `show course` uri
4. `php artisan dusk --filter testTask2StoreCourseMissingFields`: Tests that the user is redirected back if not all fields are filled.
5. `php artisan test --filter testTask2StoreSubmitsInvalidFaculty`: Ensures that only valid faculties can be submitted and that the user is redirected back when validation fails
6. `php artisan test --filter testTask2StoreSubmitsInvalidEcts`: Ensures that only numeric values for the ects input can be submitted and that the user is redirected back when validation fails
7. `php artisan dusk --filter testTask3IndexShowsCourses`: Tests that the index page contains information regarding the created courses and links to the correct `show` uri
8. `php artisan dusk --filter testTask3IndexHasCreateButton`: Tests that the index page has the create course button and the it goes to the create coures page
9. `php artisan dusk --filter testTask4ShowCourse`: Tests that the show pages shows the correct information and verifies the title
10. `php artisan test --filter testTask4ShowCourseGives404OnIncorrectCourse`: Ensures that going to a course page that doesn't exist returns a 404
11. `php artisan dusk --filter testTask5EditCourse`: Tests that a given course can have an edit page and that that page has the correct inputs.
12. `php artisan test --filter testTask5EditCourseGives404OnIncorrectCourse`: Ensures that going to a course edit page that doesn't exist returns a 404
13. `php artisan dusk --filter testTask6UpdateCourseValidFields`: Ensures that the task is updated appropriately through the form and that the user is redirected correctly.
14. `php artisan dusk --filter testTask6UpdateCourseMissingFields`: Ensures that fields are validated and you are redirected back to the edit form when a field is missing
15. `php artisan dusk --filter testTask7DeleteCourse`: Deletes a course and makes sure that the user is redirected back to the index page. Then check that the former course page no longer display information regarding the course that was deleted
16. `php artisan test --filter testTask7DeletedCourseReturns404`: Ensures that a deleted course page returns 404 when deleted.


## Logic

### Route overview
The following routes should be created and wired to an action (through a controller):

| URL | Method | Description                                 |
| -------- | ------ |---------------------------------------------|
| /courses     | GET   | Shows index page with a list of all courses |
| /courses/create | GET | Displays the form that creates a new course |
| /courses | POST | Creates a new course                        |
|/courses/{course} | GET | Shows the {course}                          |
| /courses/{course}/edit | GET | Displays the form that updates the course   |
| /courses/{course} | PUT | Updates the {course}                        |
| /courses/{course} | DELETE | Deletes the {course}                        |

We will work with two models: `Course` and `Faculty`.
Both models have their own ID, which is a unique identifier provided by the system.

The Faculty model has already been provided and follows the E/R diagram listed below.

The Course model has the following required fields: name, code, ects (the ects value of the course), description (*ie*, a text describing the content of the course), faculty_id (remember to create a foreign key in your migration).

![](https://i.imgur.com/fO9dTx3.png)

You can find a complete example of the solution here: [https://university-of-miguel.faurskov.dev](https://university-of-miguel.faurskov.dev)

![](https://i.imgur.com/vSMgPqv.png)

## Tasks

### 0. Preliminary work
Before you can run your tests, you need to create the `Course` model and an accompanying migration, such that your database is up on running.

You should create appropriate controllers throughout the development of your task. Logic defined directly within your `routes/web.php` is frowned upon and the current logic should be moved (this is not requirement, but a good practice).

We have provided the faculties for you ahead of time, you just have to seed them to your database. You can run the seeder using the `php artisan db:seed` after your migrations, or during migration using the `--seed` flag: `php artisan migrate:fresh --seed`

### 1. Course: create

This page is accessed using the url`/courses/create` and displays a form with the necessary inputs to create a course. This form should have the id `course-form`. You should have the following input:

* `code`, for the code.
    * This should be an `input` with the name `code` and type `text`
* `name`, for the name.
    * This should be an `input` with the name `name` and type `text`
* `ects`, for the ECTS value.
    * This should be an `input` with the name `ects` and type `number`
* `faculty`:
    * This field should be a select tag that shows all the faculties (value is the id of the faculty, and every option shows the faculty's name).
* `description`, for the description.
    * This should be a `textarea` with the name `description`

Lastly, a submit input is also presented to submit the form to the URL `/courses` with using the `POST` method.

**Hint:** Please look into URL generation if you are having issues here: [https://laravel.com/docs/urls#generating-urls](https://laravel.com/docs/urls#generating-urls). We propose you stick to the `route(..)` method but you are free to choose.

#### Tests

- `php artisan dusk --filter testTask1CreateCourse`: Tests that the form can be filled correctly
- `php artisan dusk --filter testTask1NotUsingStaticContent`: Tests that you are not simply hardcoding the faculties, but actually retrieving them from the database

### 2. Course: store


The form from task #1 should send a request to the `/courses` using the `POST` method:

This request should be validated, such that all fields are required to be present, ensure that the picked faculty exists (invalid inputs aren't accepted) and that ects is numeric.

**Hint:** To validate an input, you can create your own logic. For example, to valide a required value is present, you can check if the `request` contains the keys, and check if the key has a valid value (in the case of ects, it should be a number). Nevertheless, we recommend you to take a look about how Laravel manages validation: https://laravel.com/docs/validation .

A failing validation simply returns the user to the create view (if you want, you can restore the old input using the `old` method [https://laravel.com/docs/requests#retrieving-old-input](https://laravel.com/docs/requests#retrieving-old-input), however this is not part of the assignment and won't be graded).

When the request passes the validation, it should create a new course model, persist it to the database and finish by redirecting the user to the course show page `course/{course}`. At this point, this page has yet to be implemented, thus getting a 404 is fine as long as the URI is correct.

This means that if you create a course with the id of `2` then the user should be redirected to `courses/2`

**Hint:** You can read more about redirects here: [https://laravel.com/docs/responses#redirects](https://laravel.com/docs/responses#redirects). You should use a route redirection rather than passing the complete uri.

If you are stuck getting a `419` error, remember about `csrf` protection.

#### Tests

- `php artisan dusk --filter testTask2StoreCourseValidInput`: Tests that the form can be submitted and that the user is redirected to the `show` uri
- `php artisan dusk --filter testTask2StoreCourseMissingFields`: Tests that the user is redirected back if not all fields are filled.
- `php artisan test --filter testTask2StoreSubmitsInvalidFaculty`: Ensures that only valid faculties can be submitted and that the user is redirected back when validation fails
- `php artisan test --filter testTask2StoreSubmitsInvalidEcts`: Ensures that only numeric values for the ects input can be submitted and that the user is redirected back when validation fails

### 3. Courses: index

This page is accessed using the route `/courses` and it will display a list of all the courses in the system.

Every course displayed must be inside in an HTML tag with class `course`, as shown in the current course `courses/index.blade.php` file, line 18 to 25 and line 26 to 33.

We have provided a template ahead of time, but you are free to structure this tag however you want and deviate from the boilerplate code. The only requirements are that:

- The `name` of the course is shown
- The `name` of the faculty the course belongs to is shown
- The `description` of the course is shown
- A link with the class `course-details`that takes you to the details' page of the corresponding course.

Lastly, a link should be created that navigates to `courses/create` with the _id_ `create-course`.
If you are following the template, then the id is already filled, and you just need to provide the correct link.

#### Tests

- `php artisan dusk --filter testTask3IndexShowsCourses`: Tests that the index page contains information regarding the created courses and links to the correct `show` uri
- `php artisan dusk --filter testTask3IndexHasCreateButton`: Tests that the index page has the create course button and the it goes to the create coures page

### 4. Course: show

This page is accessed via the URL `/courses/{course}` and displays all the information associated with the course `{course}` and their associated faculty.

If no course is found, a 404 status code header should be returned.

The current template already gives you some guidelines on how you can structure this page, but once again, you are of course free to do as you please as long as the information is displayed.

The page should display the following information:
- `name` of the course
- `code` of the course
- `ects` of the course (this should be suffixed with ` ECTS` such that a 10 ects course says `10 ECTS`)
- `description` of the course
- `name` and `code` of the associated faculty. Shown with the code in parentheses such that `Faculty of Health Sciences` becomes `Faculty of Health Sciences (SUN)`.
- `description` of the associated faculty.

Lastly, it should also update the title of the page to match the name of the course.

#### Tests

- `php artisan dusk --filter testTask4ShowCourse`: Tests that the show pages shows the correct information and verifies the title
- `php artisan test --filter testTask4ShowCourseGives404OnIncorrectCourse`: Ensures that going to a course page that doesn't exist returns a 404

### 5. Course: edit

On the `show` page, you just created, a button has been created to update the current course (if you plan on using your own design, make sure the edit button has the `edit-course` id). This button currently goes nowhere. Start by wiring it to the correct URL `course/{course}/edit`

The edit page (`courses/{course}/edit`) should closely mimic your `create` page with the **exact** same inputs and submit button, as well as the form having the id `course-form`. The only thing that separates the two:

- The input field should all be prefilled with the information from the course you're trying to edit. This means that the _name input_ should contain the name of the course, making updating easier.
- The forms' method should be PUT instead (**Hint**: You may encounter some issues sending a PUT request through a simple HTML form, if so you can read more here: https://laravel.com/docs/blade#method-field)
- The forms' action should point to the update URL `course/{course)`

If no course is found when navigating to the edit page, a 404 status code should be returned.

#### Tests

- `php artisan dusk --filter testTask5EditCourse`: Tests that a given course can have an edit page and that that page has the correct inputs.
- `php artisan test --filter testTask5EditCourseGives404OnIncorrectCourse`: Ensures that going to a course edit page that doesn't exist returns a 404

### 6. Course: update

The form from task #5 should send a request to the `/courses/{course}` using the PUT method.

Again, this request should be validated, such that all fields are required to be present (read more [about validation here](https://laravel.com/docs/validation)) and ensure that the picked faculty exists (invalid inputs aren't accepted).
A failing validation simply returns the user to the edit form (if you want, you can restore the old input using the `old` method [https://laravel.com/docs/requests#retrieving-old-input](https://laravel.com/docs/requests#retrieving-old-input), however this is not part of the assignment and won't be graded).

When the request passes validation it should __update__ the course model, persist the changes to the database and finish by redirecting the user to the course show page `course/{course}`.


#### Tests

- `php artisan dusk --filter testTask6UpdateCourseValidFields`: Ensures that the task is updated appropriately through the form and that the user is redirected correctly.
- `php artisan dusk --filter testTask6UpdateCourseMissingFields`: Ensures that fields are validated and you are redirected back to the edit form when a field is missing


### 7. Course: destroy

The show page already contains a _delete_ course button, however you'll notice that it is not presenàly working.

The button is enclosed within a form (why is that?), however the correct http method is not currently used.
Update the form such that:

1. It submits a `DELETE` requests to the `/courses/{course}` url
2. The request is then processed and delete the course from the database
3. When done, redirect the user to the index `/courses` page.

**Hint:** You may encounter some issues sending a delete request through a simple HTML form, if so you can read more here: [https://laravel.com/docs/blade#method-field](https://laravel.com/docs/blade#method-field)

If you wish to deviate from the template, please make sure the delete button has the id `delete-course`

#### Tests

- `php artisan dusk --filter testTask7DeleteCourse`: Deletes a course and makes sure that the user is redirected back to the index page. Then check that the former course page no longer display information regarding the course that was deleted
- `php artisan test --filter testTask7DeletedCourseReturns404`: Ensures that a deleted course page returns 404 when deleted.
