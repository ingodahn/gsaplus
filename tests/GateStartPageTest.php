<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GateStartPageTest extends TestCase {
  public function testTexts() {
    $this->visit('/')
         ->see('GSA online plus')
         ->see('Gesundheitsstraining Stressbewältigung am Arbeitsplatz')
         ->see('Registrierung')
         ->see('Login')
         ;
  }

  public function testLinks() {
    // $this->visit('/')
    //      ->see('GSA online plus')
    //      ->see('Gesundheitsstraining Stressbewältigung am Arbeitsplatz')
    //      ->see('Registrierung')
    //      ->see('Login')
    //      ;
  }
}
