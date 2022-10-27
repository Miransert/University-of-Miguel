<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Faculty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTask2StoreSubmitsInvalidFaculty(): void
    {
        $this->withHeaders(['referer' => '/courses/create'])->post('/courses', [
            'name'        => 'DataMatisk Semantik',
            'code'        => 'DaSe 101',
            'ects'        => 15,
            'description' => 'This is a test :D',
            'faculty'     => Faculty::max('id') + rand(1, 100)
        ])->assertRedirect('/courses/create');
    }

    public function testTask2StoreSubmitsInvalidEcts(): void
    {
        $this->withHeaders(['referer' => '/courses/create'])->post('/courses', [
            'name'        => 'DataMatisk Semantik',
            'code'        => 'DaSe 101',
            'ects'        => "hello world",
            'description' => 'This is a test :D',
            'faculty'     => Faculty::pluck('id')->random(1)
        ])->assertRedirect('/courses/create');
    }

    public function testTask4ShowCourseGives404OnIncorrectCourse() : void
    {
        $this->get('/courses/22')->assertStatus(404);
    }

    public function testTask5EditCourseGives404OnIncorrectCourse() : void
    {
        $this->get('/courses/22/edit')->assertStatus(404);
    }

    public function testTask7DeletedCourseReturns404() : void
    {
        $this->withHeaders(['referer' => '/courses/create'])->post('/courses', [
            'name'        => 'DataMatisk Semantik',
            'code'        => 'DaSe 101',
            'ects'        => 15,
            'description' => 'This is a test :D',
            'faculty'     => Faculty::pluck('id')->first()
        ]);
        $this->get('/courses/1')->assertOk();
        $this->delete('/courses/1');
        $this->get('/courses/1')->assertNotFound();
    }
}
