<?php

namespace Tests\Browser;

use App\Models\Faculty;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\FacultySeeder;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert as PHPUnit;
use Tests\Browser\Pages\Course;
use Tests\Browser\Pages\CreateCourse;
use Tests\Browser\Pages\Index;
use Tests\DuskTestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use function Symfony\Component\String\b;

class CourseTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testTask1CreateCourse()
    {
        $this->browse(function(Browser $browser) {
            $browser->visit(new CreateCourse())->formHasAllElements(Faculty::pluck('id'));
        });
    }

    public function testTask1NotUsingStaticContent()
    {
        $facultyOfNiels = new Faculty();
        $faker = Factory::create();
        $facultyOfNiels->name = $faker->name;
        $facultyOfNiels->id = $faker->numberBetween(100, 2500);
        $facultyOfNiels->description = $faker->paragraph();
        $facultyOfNiels->code = $faker->bothify('###-???');
        $facultyOfNiels->save();
        $this->browse(function(Browser $browser) use ($facultyOfNiels) {
            $browser->visit(new CreateCourse())->select('faculty', $facultyOfNiels->id)->assertSelected('faculty', $facultyOfNiels->id)->assertSee($facultyOfNiels->name);
        });
    }

    public function testTask2StoreCourseValidInput()
    {
        $this->browse(function(Browser $browser) {
            $browser->visit(new CreateCourse())->submitForm(Faculty::pluck('id'));
        });
    }

    public function testTask2StoreCourseMissingFields()
    {
        $this->browse(function(Browser $browser) {
            $browser->visit(new CreateCourse())->submitFormWithMissingFields(Faculty::pluck('id'));
        });
    }

    public function testTask3IndexShowsCourses()
    {
        $faculties = Faculty::all()->shuffle();
        $faculty1 = $faculties->pop();
        $faculty2 = $faculties->pop();

        $this->browse(function(Browser $browser) use ($faculty2, $faculty1) {
            $browser->visit(new CreateCourse())->submitCustomForm("DataMatisk Semantik", "DaSe 101", $faculty1->id, 'This is a description for the first course', 20);
            $browser->visit(new CreateCourse())->submitCustomForm("Radiatorer for Seniorer", "Radiate 101", $faculty2->id, 'This is another description for the second course', 10);

            $courses = $browser->visit(new Index())->elements('.course');
            $dataMatiskIndex = Str::contains($courses[0]->getText(), 'DataMatisk Semantik') ? 0 : 1;
            $radiatorerIndex = $dataMatiskIndex == 0 ? 1 : 0;
            $this->seeInElement($courses[$dataMatiskIndex], 'DataMatisk Semantik');
            $this->seeInElement($courses[$dataMatiskIndex], $faculty1->name);
            $this->seeInElement($courses[$dataMatiskIndex], 'This is a description for the first course');
            assertEquals(url('/courses/1'), $courses[$dataMatiskIndex]->findElement(WebDriverBy::className("course-details"))->getAttribute("href"));

            $this->seeInElement($courses[$radiatorerIndex], 'Radiatorer for Seniorer');
            $this->seeInElement($courses[$radiatorerIndex], $faculty2->name);
            $this->seeInElement($courses[$radiatorerIndex], 'This is another description for the second course');
            assertEquals(url('/courses/2'), $courses[$radiatorerIndex]->findElement(WebDriverBy::className("course-details"))->getAttribute("href"));
        });
    }


    public function testTask3IndexHasCreateButton()
    {
        $this->browse(function(Browser $browser) {
            $browser->visit(new Index())->assertPresent('#create-course')
                ->click('#create-course')
                ->assertPathIs('/courses/create');
        });
    }

    public function testTask4ShowCourse()
    {
        $faculties = Faculty::all()->shuffle();
        $faculty = $faculties->first();
        $faker = Factory::create();
        $name = $faker->sentence;
        $description = $faker->paragraph(3);
        $code = $faker->bothify('??-??##??-##');
        $ects = $faker->numberBetween(1, 30);
        $this->browse(function(Browser $browser) use ($ects, $description, $code, $faculty, $name) {
            $browser->visit(new CreateCourse())->submitCustomForm($name, $code, $faculty->id, $description, $ects);
            $browser->assertSee($name)
                ->assertSee($code)
                ->assertSee("$ects ECTS")
                ->assertSee($description)
                ->assertSee("{$faculty->name} ({$faculty->code})")
                ->assertSee($faculty->description)
                ->assertTitleContains($name);
        });
    }

    public function testTask5EditCourse()
    {
        $faculties = Faculty::all()->shuffle();
        $faculty = $faculties->first();
        $faker = Factory::create();
        $name = $faker->sentence;
        $description = $faker->paragraph(3);
        $code = $faker->bothify('??-??##??-##');
        $ects = $faker->numberBetween(1, 30);

        $this->browse(function(Browser $browser) use ($ects, $description, $code, $faculty, $name) {
            $browser->visit(new CreateCourse())->submitCustomForm($name, $code, $faculty->id, $description, $ects);
            $browser->assertPresent('#edit-course')
                ->click('#edit-course')
                ->assertPathIs('/courses/1/edit')
                ->assertInputValue('code', $code)
                ->assertInputValue('name', $name)
                ->assertInputValue('ects', $ects)
                ->assertSelected('faculty', $faculty->id)
                ->assertInputValue('description', $description);
            $formElement = $browser->element('@form');
            $formMethod = Str::of($formElement->getAttribute('method'))->lower()->trim()->toString();
            $formAction = Str::of($formElement->getAttribute('action'))->lower()->trim()->toString();
            assertEquals('post', $formMethod, "The edit course form is not using the correct method. This may seem counter intuitive, but standard forms html form don't support put.");
            assertEquals(url('/courses/1'), $formAction, "The action is not pointing to the correct url, are you using the route(..) method?");
        });
    }

    public function testTask6UpdateCourseValidFields()
    {
        $faculties = Faculty::all()->shuffle();
        $faculty = $faculties->pop();
        $faker = Factory::create();
        $name = $faker->sentence;
        $description = $faker->paragraph(3);
        $code = $faker->bothify('??-??##??-##');
        $ects = $faker->numberBetween(1, 30);

        $newName = $faker->sentence;
        $newDescription = $faker->paragraph(3);
        $newCode = $faker->bothify('??-??##??-##');
        $newEcts = $ects + 1;
        $newFaculty = $faculties->pop();

        $this->browse(function(Browser $browser) use ($newCode, $newName, $newEcts, $newFaculty, $newDescription, $ects, $description, $faculty, $code, $name) {
            $browser->visit(new CreateCourse())->submitCustomForm($name, $code, $faculty->id, $description, $ects);
            $browser->click('#edit-course')
                ->type('code', $newCode)
                ->type('name', $newName)
                ->type('ects', $newEcts)
                ->select('faculty', $newFaculty->id)
                ->type('description', $newDescription)
                ->screenshot("fml")
                ->click('form[id=course-form] input[type=submit],form[id=course-form] button[type=submit]')
                ->assertPathIs('/courses/1')
                ->assertSee($newCode)
                ->assertSee($newName)
                ->assertSee($newEcts)
                ->assertSee($newFaculty->name)
                ->assertSee($newDescription);
        });
    }

    public function testTask6UpdateCourseMissingFields()
    {
        $faculties = Faculty::all()->shuffle();
        $faculty = $faculties->pop();
        $faker = Factory::create();
        $name = $faker->sentence;
        $description = $faker->paragraph(3);
        $code = $faker->bothify('??-??##??-##');
        $ects = $faker->numberBetween(1, 30);

        $this->browse(function(Browser $browser) use ($ects, $description, $faculty, $code, $name) {
            $browser->visit(new CreateCourse())->submitCustomForm($name, $code, $faculty->id, $description, $ects);
            $browser->click('#edit-course')
                ->type('code', "123")
                ->type('name', "")
                ->click('form[id=course-form] input[type=submit],form[id=course-form] button[type=submit]')
                ->assertPathIs('/courses/1/edit'); // Validation failed, you should stay on the edit page
        });
    }

    public function testTask7DeleteCourse()
    {
        $faculties = Faculty::all()->shuffle();
        $faculty = $faculties->pop();
        $faker = Factory::create();
        $name = $faker->sentence;
        $description = $faker->paragraph(3);
        $code = $faker->bothify('??-??##??-##');
        $ects = $faker->numberBetween(1, 30);
        $this->browse(function(Browser $browser) use ($ects, $description, $code, $faculty, $name) {
            $browser->visit(new CreateCourse())->submitCustomForm($name, $code, $faculty->id, $description, $ects);
            $browser->assertPresent('#delete-course')
                ->click('#delete-course')
                ->assertPathIs('/courses')
                ->visit('/courses/1')
                ->assertDontSee($name);
        });

    }

    private function seeInElement(RemoteWebElement $element, $text)
    {
        $name = $element->getTagName() . Str::of($element->getAttribute("class"))->explode(" ")->map(fn($s) => ".$s")->join("");
        assertTrue(
            Str::contains($element->getText(), $text),
            "Did not see expected text [{$text}] within element [$name]."
        );

        return $this;
    }
}
