A simple PHP wrapper for the new JSON-based REST web service API of CollectiveAccess

Please visit http://www.collectiveaccess.org for more information and refer to
http://docs.collectiveaccess.org for detailed information on the service API and 
other features of the core software.

To use this library, simply copy all the project files into a subdirectory of your
project and include the class file of the service you want to use. 

For example:

```php
require './cawrapper/ItemService.php':
$client = new ItemService("http://localhost/","ca_objects","GET",1);
$result = $client->request();
print_r($result->getRawData());
```

This should get you a generic summary for the object record with object_id 1.

Here are some more simple examples for the other service endpoints to get you started:

```php
$vo_client = new ModelService("http://localhost/","ca_entities");
$vo_client->setRequestBody(array("types" => array("corporate_body")));
$vo_result = $vo_client->request();

$vo_result->isOk() ? print_r($vo_result->getRawData()) : print_r($vo_result->getErrors());
```

```php
$vo_client = new SearchService("http://localhost/","ca_objects","*");
$vo_client->setRequestBody(array(
	"bundles" => array(
		"ca_objects.access" => array("convertCodesToDisplayText" => true),
		"ca_objects.status" => array("convertCodesToDisplayText" => true),
		"ca_entities.preferred_labels.displayname" => array("returnAsArray" => true)
	)
));
$vo_result = $vo_client->request();

$vo_result->isOk() ? print_r($vo_result->getRawData()) : print_r($vo_result->getErrors());
```