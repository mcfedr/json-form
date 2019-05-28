<?php

declare(strict_types=1);

namespace Mcfedr\JsonFormBundle\Controller;

use Mcfedr\JsonFormBundle\Exception\InvalidFormHttpException;
use Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException;
use Mcfedr\JsonFormBundle\Exception\MissingFormHttpException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property ContainerInterface $container
 */
trait JsonControllerTrait
{
    public function createJsonForm(string $type, $data = null, array $options = []): FormInterface
    {
        $this->checkCsrf($options);

        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    public function createJsonFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        $this->checkCsrf($options);

        return $this->container->get('form.factory')->createBuilder(FormType::class, $data, $options);
    }

    /**
     * @param callable $preValidation callback to be called before the form is validated
     *
     * @throws \Mcfedr\JsonFormBundle\Exception\InvalidFormHttpException
     * @throws \Mcfedr\JsonFormBundle\Exception\MissingFormHttpException
     * @throws \Mcfedr\JsonFormBundle\Exception\InvalidJsonHttpException
     */
    protected function handleJsonForm(FormInterface $form, Request $request, callable $preValidation = null): void
    {
        $bodyJson = $request->getContent();
        if (!($body = json_decode($bodyJson, true))) {
            throw new InvalidJsonHttpException();
        }

        if (!isset($body[$form->getName()])) {
            throw new MissingFormHttpException($form);
        }

        $form->submit($body[$form->getName()]);

        if ($preValidation) {
            $preValidation();
        }

        if (!$form->isValid()) {
            throw new InvalidFormHttpException($form);
        }
    }

    private function checkCsrf(array &$options): void
    {
        if (!\array_key_exists('csrf_protection', $options) && $this->jsonControllerGetParameter('form.type_extension.csrf.enabled')) {
            $options['csrf_protection'] = false;
        }
    }

    private function jsonControllerGetParameter(string $name)
    {
        // If the controller isnt registered as a service, the container will
        // be the full Container, as was default in 3.0
        if ($this->container->has('parameter_bag')) {
            return $this->container->get('parameter_bag')->get($name);
        }
        if (method_exists($this->container, 'getParameter')) {
            return $this->container->getParameter($name);
        }
        if (method_exists($this, 'getParameter')) {
            return $this->getParameter($name);
        }

        throw new \LogicException('Cannot get parameters');
    }
}
