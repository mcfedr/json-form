# Json Form helper

Simply extend the `JsonController` and then use forms as you would normally, but they now expect to receive JSON.
Something like this.

[![Latest Stable Version](https://poser.pugx.org/mcfedr/json-form/v/stable.png)](https://packagist.org/packages/mcfedr/json-form)
[![License](https://poser.pugx.org/mcfedr/json-form/license.png)](https://packagist.org/packages/mcfedr/json-form)
[![Build Status](https://travis-ci.org/mcfedr/json-form.svg?branch=master)](https://travis-ci.org/mcfedr/json-form)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/86c5d646-c3d8-444f-b27c-6bf3a2a727a0/mini.png)](https://insight.sensiolabs.com/projects/86c5d646-c3d8-444f-b27c-6bf3a2a727a0)

## Install

### Composer

    php composer.phar require mcfedr/json-form
### AppKernel

Include the bundle in your AppKernel

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Mcfedr\JsonFormBundle\JsonFormBundle()

## JSON

The expected JSON will be just like that form values that would be sent.

Suppose you have the following form type

    class AccountType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('name');
        }
    
        public function getName()
        {
            return 'account';
        }
    }

Then the JSON should be

    {
        "account": {
            "name": "Fred"
        }
    }
    

## Example

    class ActionController extends JsonController
        /**
         * @Route("/actions/{uuid}", requirements={"uuid"="[a-z0-9-]{36}"})
         * @Method("POST")
         */
        public function actionCreateAction(Request $request, $uuid) {
            $action = new Action();
            $form = $this->createForm(new ActionType(), $action);
            $this->handleJsonForm($form, $request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();

            return new JsonResponse([
                'action' => $action
            ]);
        }
    }

## Tests

To run the tests

    ./vendor/bin/phpunit
