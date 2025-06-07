<?php

namespace App\Tests;
use PHPUnit\Framework\TestCase;
use App\Helpers\Validator;

class ValidatorTest extends TestCase
{
    public function testValidEmail()
    {
        $this->assertTrue(Validator::validateEmail("test@example.com"));
    }

    public function testInvalidEmail()
    {
        $this->assertFalse(Validator::validateEmail("test@.com"));
        $this->assertFalse(Validator::validateEmail("test@com"));
        $this->assertFalse(Validator::validateEmail("test.com"));
        $this->assertFalse(Validator::validateEmail("test@com."));
    }

    // this test checks if the user provides all required fields. as this method returns an array of missing fields
    public function testValidateRequired()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $requiredFields = ['name', 'email', 'password'];

        $this->assertEmpty(Validator::validateRequired($data, $requiredFields));
        $this->assertNotEmpty(Validator::validateRequired(['name' => 'some name'], $requiredFields));
    }
    
   public function testSanitizeString()
    {
        $input = "  <script>alert('XSS');</script> Hello World!     ";
        $expected = "alert(&#039;XSS&#039;); Hello World!";
        $this->assertEquals($expected, Validator::sanitizeString($input));
    }


}