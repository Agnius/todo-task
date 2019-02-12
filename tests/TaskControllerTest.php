<?php

use App\Models\Role;
use App\Models\User;

class TaskControllerTest extends TestCase
{
    public function testShouldCreateTask()
    {
        factory(Role::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('/api/v1/tasks', [
                'description' => 'abbbcd'
            ])->seeJsonEquals([
                'data' => true
            ])
        ;
    }
}
