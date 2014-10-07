<?php
/**
 * Created by mcfedr on 07/10/14 22:57
 */

namespace Mcfedr\JsonForm;

use Mcfedr\JsonForm\Controller\JsonController;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

class JsonControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleForm()
    {
        /** @var JsonController $controller */
        $controller = $this->getMockForAbstractClass('Mcfedr\JsonForm\Controller\JsonController');
        $form = Forms::createFormFactory()->createBuilder()
            ->add('one', 'text')
            ->getForm();

        $request = new Request([], [], [], [], [], [], json_encode([
            'form' => [
                'one' => 'hi'
            ]
        ]));

        $this->invokeMethod($controller, 'handleJsonForm', [$form, $request]);

        $this->assertEquals($form->getData()['one'], 'hi');
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    private function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
