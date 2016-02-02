<?php

namespace Mcfedr\JsonFormBundle\Tests\Controller;

use Mcfedr\JsonFormBundle\Controller\JsonController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class JsonControllerCheckboxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonController
     */
    private $controller;

    /**
     * @var Form
     */
    private $form;

    public function setUp()
    {
        $this->controller = $this->getMockForAbstractClass('Mcfedr\JsonFormBundle\Controller\JsonController');
        $validator = Validation::createValidator();
        $this->form = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory()
            ->createBuilder()
            ->add('one', CheckboxType::class)
            ->getForm();
    }

    public function testHandleTrue()
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'form' => [
                'one' => true
            ]
        ]));

        $this->invokeMethod($this->controller, 'handleJsonForm', [$this->form, $request]);

        $this->assertEquals($this->form->getData()['one'], true);
    }

    public function testHandleFalse()
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'form' => [
                'one' => false
            ]
        ]));

        $this->invokeMethod($this->controller, 'handleJsonForm', [$this->form, $request]);

        $this->assertEquals($this->form->getData()['one'], false);
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
