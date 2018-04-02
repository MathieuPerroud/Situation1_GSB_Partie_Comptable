<code>
    <?php
    /**
    * fct short summary.
    *
    * fct description.
    *
    * @version 1.0
    * @author Mathieu
    */
    use PHPUnit_Framework_TestCase;
    include('../pear/phpunit.php');
    require_once('PHPUnit/Autoload.php');
    
    class fct extends PHPUnit_Framework_TestCase
    {
        public $val1;
        public $val2;
        public function estDateDepaseeTest()
        {
        $this ->    assertTrue( estDateDepassee('23/03/2018') );
        }
        protected function setUp()
        {
            $this->value1 = 2;
            $this->value2 = 3;
        }
        public function testPass()
        {
            $this->assertTrue($this->value1 + $this->value2 == 5);
        }
    }
    ?>
</code>
*


