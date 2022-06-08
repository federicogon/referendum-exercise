<?php

namespace Tests\Unit;

use Tests\TestCase;

class CodeTest extends TestCase
{
   
    /**
     * Run seeder for test
     */
    public function test_seed_database() 
    {
        $this->seed();

        return $this->assertDatabaseCount('voters',100,'sqlite');
    }

    public function test_run_all_create_referendum(){

        return $this->assertTrue($this->create_referendum());
    }
    /**
     * Create test Referendum for test
     */
    public function create_referendum() 
    {
        try {
            
            $this->post('api/referendum/create',
            [
                
                "title" => "Referendum 1",
                "description" => "Referendum 1 Description",
                "order" => 105.1,
                "questions" => [
                    "Referendum 1 Question number 1",
                    "Referendum 1 Question number 2",
                    "Referendum 1 Question number 3"
                    ]
            ]);
                
            $this->post('api/referendum/create',
                
            [
                
                "title" => "Referendum 2",
                "description" => "Referendum 2 Description",
                "order" => 80.1,
                "questions" => [
                    "Referendum 2 Question number 1",
                    "Referendum 2 Question number 2",
                    "Referendum 2 Question number 3"
                    ]
            ]);
                
            
            
            $this->post('api/referendum/create',
            [
                "title" => "Referendum 3",
                "description" => "Referendum 3 Description",
                "order" => 101.1,
                "questions" => [
                    "Referendum 3 Question number 1",
                    "Referendum 3 Question number 2",
                    "Referendum 3 Question number 3"
                    ]
            ]);

            
            $this->post('api/referendum/create',
            
            [
                "title" => "Referendum 4",
                "description" => "Referendum 4 Description",
                "order" => 100.1,
                
                "questions" => [
                    "Referendum 4 Question number 1",
                    "Referendum 4 Question number 2",
                    "Referendum 4 Question number 3"
                    ]
            ]);
                
            
            $this->post('api/referendum/create',
            [
                "title" => "Referendum 5",
                "description" => "Referendum 5 Description",
                "order" => 100.1,
                
                "questions" => [
                    "Referendum 5 Question number 1",
                    "Referendum 5 Question number 2",
                    "Referendum 5 Question number 3"
                    ]
            ]);
            
            return true;
        } catch (\Throwable $th) {
            return false;
        }
        
    }
    
    


}
