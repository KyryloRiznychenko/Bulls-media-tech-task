## Before to start test the app, you should do the next steps

- Make/setup an .env
- php artisan key:generate
- After the all preparations, you can run tests.

## "Можно изменить код или дописать комментарий над методом/классами как они будут видоизменяться если владелец интернет-магазина захочет добавить отправку через Укрпочту, Джастин и другие курьерки Как изменится код, если курьеских будет 15?"

- Through OOP
- You must inherit the DeliveryProviderInterface interface from your new delivery service entity.
- After that, you must implement methods of the interface.
- After that, you need to go to the DeliveryServiceProvider and make binds.
- And in the end, you can use these deliver services in the DeliveryService.

## "2.4) Если у клиента есть проблема с доставкой заказов. Клиент отправляет данные, но поддержка курьерской службы говорит, что не получает данные от текущего сервиса."

- Here is a lot o ways for resolving it. You can make a custom logger which will push email/mobile and ent. notification
  or create a table for this goal (tracing)
