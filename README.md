ocart-cli
=========

CLI Interface helper for OpenCart. Useful for cron commands, for example.

Put folder into opencart root folder(a dir, contains admin/ catalog/ image/)
e.g.:
/var/www/my_cool_opencart_shop/cli
And just try this one:
php cli/run.php example_operations_currencies

Some help available with:
php cli/run.php -h


Directory structure looks like one in catalog/ or admin, but you don't need langs and templates here and controllers dir
renamed to "classes".

Take a look @ classes/example/ExampleOperations.php
You will find there a cli_currencies method And this one is called when you cast
php cli/run.php example_operations_currencies
in shell.
Naming has some rules:
1) Name your classes group(example)
2) Name every class you will need as capitalized class group(Example) plus capitalized class identifier(+Operations)
And give name class to the file and the class inside (ExampleOperations.php with class ExampleOperations)
3) Don't forget to inherit class from basic (BasicCLIObject)
4) Add public method cli_+%your_method% to the class(cli_currencies)
And now you may call your method with group_identifier_method (example_operations_currencies)

*NB : all params not reserved by ocart-cli will be passed to your method in order you put them into shell.
Don't forget to handle them!