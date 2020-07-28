<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Tests\Controller;

use Mcfedr\JsonFormBundle\Controller\JsonController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

/**
 * @internal
 */
final class JsonControllerCheckboxTest extends TestCase
{
    /**
     * @var MockObject^JsonController
     */
    private $controller;

    /**
     * @var Form
     */
    private $form;

    protected function setUp(): void
    {
        $this->controller = $this->getMockBuilder(JsonController::class)->disableOriginalConstructor()->getMock();
        $validator = Validation::createValidator();
        $this->form = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory()
            ->createBuilder()
            ->add('one', CheckboxType::class)
            ->getForm()
        ;
    }

    public function testHandleTrue(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'form' => [
                'one' => true,
            ],
        ]));

        $this->invokeMethod($this->controller, 'handleJsonForm', [$this->form, $request]);

        static::assertEquals($this->form->getData()['one'], true);
    }

    public function testHandleFalse(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'form' => [
                'one' => false,
            ],
        ]));

        $this->invokeMethod($this->controller, 'handleJsonForm', [$this->form, $request]);

        static::assertEquals($this->form->getData()['one'], false);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object     instantiated object that we will run method on
     * @param string $methodName Method name to call
     * @param array  $parameters array of parameters to pass into method
     *
     * @return mixed method return
     */
    private function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(\get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
