# Json Form helper

Simply use the `JsonControllerTrait` and then use forms as you would normally,
but they now expect to receive JSON.

[![Latest Stable Version](https://poser.pugx.org/mcfedr/json-form/v/stable.png)](https://packagist.org/packages/mcfedr/json-form)
[![License](https://poser.pugx.org/mcfedr/json-form/license.png)](https://packagist.org/packages/mcfedr/json-form)
[![Build Status](https://travis-ci.org/mcfedr/json-form.svg?branch=master)](https://travis-ci.org/mcfedr/json-form)

## Install

### Composer

```bash
php composer.phar require mcfedr/json-form
```

### AppKernel

Include the bundle in your AppKernel

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Mcfedr\JsonFormBundle\McfedrJsonFormBundle()
```

## JSON

The expected JSON will be just like that form values that would be sent.

Suppose you have the following form type

```php
class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name');
    }

    public function getBlockPrefix()
    {
        return 'account';
    }
}
```

Then the JSON should be

```json
{
    "account": {
        "name": "Fred"
    }
}
```

## Example

```php
class AccountController extends AbstractController
    use JsonControllerTrait;

    /**
     * @Route("/accounts", methods={"POST"})
     */
    public function accountCreateAction(Request $request, $uuid) {
        $account = new Account();
        $form = $this->createJsonForm(AccountType::class, $account);
        $this->handleJsonForm($form, $request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($account);
        $em->flush();

        return $this->json([
            'account' => $account
        ]);
    }
}
```

For Symfony 3.x you will need to extend `Controller` because the trait needs
access to `getParameter` method.

## Contributing

To run the tests

```bash
./vendor/bin/php-cs-fixer fix
./vendor/bin/phpunit
./vendor/bin/phpstan analyse
```
