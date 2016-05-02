<?php

use Faker\Factory as FakerFact;
use Symfony\Component\HttpFoundation\Request as Request;



class UserManagerTest extends TestCase
{
    public function testCreateUser() {
        $faker = FakerFact::create();

        $response = $this->call('POST', '/register', [
            'name'=> $faker->name,
            'password' => $faker->password,
            'email' => $faker->email,
        ]);
//         $request2 =  new Request();
//         $request2-> '/register', 'POST',array(
//             array(),
//             array(
//         'CONTENT_TYPE'          => 'application/json',
//         'HTTP_REFERER'          => '/foo/bar',
//         'HTTP_X-Requested-With' => 'XMLHttpRequest',
//     ));
        echo $response->getStatusCode();
 
    }
     
}