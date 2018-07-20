## Application Tables
These are object relational mappers and it's exactly the same codes as you would have with normal JTable. The only difference for placing your table classes here is that you can easily access the table objects without worrying about including the correct namespaces.

Usage in view:

```php
<?php
$table 	= $this->getTable( 'TableName' );
?>
```