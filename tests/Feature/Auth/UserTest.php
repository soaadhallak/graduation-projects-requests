<?php

namespace Tests\Feature\Auth;

use App\Models\Major;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_new_student_can_register_with_valid_data()
    {
        Role::create(['name' => 'student', 'guard_name' => 'web']);
        $major = Major::factory()->create(['name' => 'هندسة برمجيات']);

        $payload = [
            'name'                  => 'test',
            'email'                 => 'test@test.com',
            'password'              => Hash::make('12345678'),
            'universityNumber'      => '123456',
            'skills'                => 'Laravel, PHPUnit, MySQL',
            'majorId'               => $major->id,
            'avatar'                => UploadedFile::fake()->image('profile_pic.jpg'),
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'avatar'],
                'token'
            ]);

        $user = User::whereEmail('test@test.com')->first();

        $this->assertDatabaseHas('student_details', [
            'user_id'           => $user->id,
            'university_number' => '123456',
            'major_id'          => $major->id
        ]);

    }

    public function test_user_can_login(){
        $user=User::factory()->create([
            'name'=>'test2',
            'email'=>'teste2@test2.com',
            'password'=>Hash::make('12345678')
        ]);

        $response=$this->postJson('/api/users/login',[
            'email'=>$user->email,
            'password'=>'12345678'
        ]);

        $response->assertOk()->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'avatar'],
                'token'
            ]);

    }

    public function test_Invalid_credentials_login(){
        $payload=[
            'email'=>'test2@test.com',
            'password'=>Hash::make('12345876')
        ];

        $response=$this->postJson('/api/users/login',$payload);

        $response->assertUnprocessable();
    }

    public function test_user_can_logout(){
        $user=User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response=$this->withToken($token)->postJson('/api/users/logout');

        $response->assertOk();
        $this->assertCount(0, $user->tokens);
    }

    public function test_user_unauthenticated(){

        $response=$this->postJson('/api/users/logout');

        $response->assertUnauthorized();
    }

}
