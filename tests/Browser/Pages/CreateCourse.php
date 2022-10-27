<?php

namespace Tests\Browser\Pages;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;
use function PHPUnit\Framework\assertEquals;

class CreateCourse extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/courses/create';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@code'        => 'input[name=code][type=text]',
            '@ects'        => 'input[name=ects][type=number]',
            '@faculty'     => 'select[name=faculty]',
            '@name'        => 'input[name=name][type=text]',
            '@description' => 'textarea[name=description]',
            '@form'        => 'form[id=course-form]',
            '@submit'      => 'form[id=course-form] input[type=submit],form[id=course-form] button[type=submit]',
        ];
    }

    public function formHasAllElements(Browser $browser, Collection $facultyIds)
    {
        $browser
            ->assertPresent('@form')
            ->assertPresent('@code')
            ->assertPresent('@name')
            ->assertPresent('@ects')
            ->assertPresent('@faculty')
            ->assertPresent('@description')
            ->assertPresent('@submit')
            ->assertSelectHasOptions('faculty', $facultyIds->toArray());

        $formElement = $browser->element('@form');
        $formMethod = Str::of($formElement->getAttribute('method'))->lower()->trim()->toString();
        $formAction = Str::of($formElement->getAttribute('action'))->lower()->trim()->toString();
        assertEquals('post', $formMethod, "The create course form is not using the correct method.");
        assertEquals(url('/courses'), $formAction, "The action is not pointing to the correct url, are you using the route(..) method?");
    }

    public function submitForm(Browser $browser, Collection $facultyIds)
    {
        $faculty = $facultyIds->random(1)->first();
        $this->submitCustomForm($browser, 'DataMatisk Semantik', 'DaSe 101', $faculty, 'This is a description for the course Datamatisk Semantik', 15)
            ->assertPathIs('/courses/1');
    }

    public function submitFormWithMissingFields(Browser $browser, Collection $facultyIds)
    {
        $browser
            ->type('code', 'DaSe 101')
            ->select('faculty', $facultyIds->random(1)->first())
            ->click('@submit')
            ->assertPathIs('/courses/create');
    }


    public function submitCustomForm(Browser $browser, string $name, string $code, int $faculty, string $description, int $ects): Browser
    {
        return $browser
            ->type('code', $code)
            ->type('name', $name)
            ->select('faculty', $faculty)
            ->type('description', $description)
            ->type('ects', $ects)
            ->click('@submit');
    }
}
