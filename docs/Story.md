# Story
The `Story` class represents a news story read by a `Burgundy` object.

## Properties
By default, a `Story` object has the following protected properties, that can be retrieved via getters:
- `spec`
- `id`
- `url`
- `title`
- `content`
- `author`
- `tags`
- `published`
- `updated`

### Specification
Get the feed specification type as a `string`. 

Typical values would be `Atom`, `RDF` or `RSS`.

**Example:**
```php
var_dump($story->getSpec());
```

**Output:**
```php
string(3) "RSS"
```

### ID
Get the `Story` identifier as a `string`.

**Example:**
```php
var_dump($story->getId());
```

**Output:**
```php
string(45) "http://www.bbc.co.uk/news/technology-36084989"
```

### URL
Get the `Story` unique URL.

**Example:**
```php
var_dump($story->getUrl());
```

**Output:**
```php
string(45) "http://www.bbc.co.uk/news/technology-36084989"
```

### Title
Get the title of a `Story` object.

**Example:**
```php
var_dump($story->getTitle());
```

**Output:**
```php
string(47) "Tech support scams target victims via their ISP"
```

### Content
Get the actual story text.

**Example:**
```php
var_dump($story->getContent());
```

**Output:**
```php
string(94) "The old tech support scam where a fraudster pretends to be a Microsoft agent takes a new turn."
```

### Author
Get the story author.

**Example:**
```php
var_dump($story->getAuthor());
```

**Output:**
```php
string(0) ""
```

>**TIP:** Sometimes, no author is specified.

### Tags
Get the tags used to classify a news story.

**Example:**
```php
var_dump($story->getTags());
```

**Output:**
```php
array(0) {
}
```

>**TIP:** Not all news feeds include tags.

### Published date/time
Get the published date/time as a `Carbon` object.

**Example:**
```php
var_dump($story->getPublished());
```

**Output:**
```php
object(Carbon\Carbon)#29 (3) {
  ["date"]=>
  string(26) "2016-06-22 00:35:41.000000"
  ["timezone_type"]=>
  int(2)
  ["timezone"]=>
  string(3) "GMT"
}
```

### Updated date/time
Get the updated date/time as a `Carbon` object.

**Example:**
```php
var_dump($story->getUpdated());
```

**Output:**
```php
object(Carbon\Carbon)#41 (3) {
  ["date"]=>
  string(26) "2016-06-22 00:35:41.000000"
  ["timezone_type"]=>
  int(2)
  ["timezone"]=>
  string(3) "GMT"
}
```

>**TIP:** Most times, the `published` and `updated` properties have the same value.

### Extra properties
Extra properties derived from extending a feed specification, can be retrieved with the `getExtra($property = null)` method.

**Examples:**
```php
// Get all the extra properties as an array
var_dump($story->getExtra());
```

```php
// Get an extra property by its key
var_dump($story->getExtra('foo'));
```

**Outputs:**
```php
array(1) {
  ["foo"]=>
  string(3) "bar"
}
```

```php
string(3) "bar"
```

>**Attention:** A `RonException` will be thrown when trying to retrieve an invalid property key.

### Array representation
Retrieve an `array` representation of the `Story` object.

The returned array will only include the following keys: `id`, `url`, `title`, `content`, `author`, `tags`, `published` and `updated`. 

**Example:**
```php
var_dump($story->toArray());
```

**Output:**
```php
array(8) {
  ["id"]=>
  string(45) "http://www.bbc.co.uk/news/technology-36084989"
  ["url"]=>
  string(45) "http://www.bbc.co.uk/news/technology-36084989"
  ["title"]=>
  string(47) "Tech support scams target victims via their ISP"
  ["content"]=>
  string(94) "The old tech support scam where a fraudster pretends to be a Microsoft agent takes a new turn."
  ["author"]=>
  string(0) ""
  ["tags"]=>
  array(0) {
  }
  ["published"]=>
  object(Carbon\Carbon)#29 (3) {
    ["date"]=>
    string(26) "2016-06-22 00:35:41.000000"
    ["timezone_type"]=>
    int(2)
    ["timezone"]=>
    string(3) "GMT"
  }
  ["updated"]=>
  object(Carbon\Carbon)#41 (3) {
    ["date"]=>
    string(26) "2016-06-22 00:35:41.000000"
    ["timezone_type"]=>
    int(2)
    ["timezone"]=>
    string(3) "GMT"
  }
}
```
