# php-json-property
## Description
This is a helper to add attributes to an array. This will help,  
if you need to represent JSON entities / classes and resolve recursively when JSON encoding.

## Usage
You can use `JsonPropertyInterface` and `JsonPropertyTrait` in the classes  
you want to add this. Calling `json_encode` will recursively resolve all embedded  
classes in the JSON properties array, leveraging `JsonSerializable`.

### Add a property
```php
$jsonPropertyClass->addJsonProperty('key', 'value');
```

### Add multiple properties
```php
$jsonPropertyClass->addJsonProperties(['key', 'value']);
```

### Remove property
```php
$jsonPropertyClass->removeJsonProperty('key');
```

### Set properties array of class
```php
$jsonPropertyClass->setJsonProperties(['key', 'value']);
```

### Get properties
Get the array without resolving embedded properties
```php
$jsonPropertyClass->getJsonProperties();
```
Get the array of properties and resolve embedded
```php
$jsonPropertyClass->getJsonProperties(true);
```

### Add typed properties
To add typed JSON properties, add a wrapper around e.g. `addJsonProperty`
```php
public function addName(string $name) {
    $this->addJsonProperty('name', $name);
}
```
