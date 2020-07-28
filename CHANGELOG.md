## 4.7.0

- Drop Symfony 3.x
- Add Symfony 5.x
- Undeprecate the JsonController

## 4.6.0
- Drop support for php less than 7.2
- Add strict_types
- Upgrade phpunit
- Add phpstan

## 4.5.0
- Fix use of getParameter in JsonControllerTrait
- Deprecate JsonController

## 4.4.0
- Change to using FormInterface instead of Form

## 4.3.0
- Don't log json errors, ends up double logging because the resulting http exception is still logged

## 4.2.0
- Add a better top level message by combining all the inner messages
