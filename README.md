## PHPP: (PHP Hypertext Preprocessor) Preprocessor

**Note**: This is an extremally super mega alpha project, don't use it in production yet!

### Why?

Because I had free time.

### Can you give us a Hello World?

```c

#keyword demon class
#keyword satanic public
#keyword devil function
#keyword kills __construct
#keyword cry echo
#keyword hell php

<?hell

demon Main {
  satanic devil kills()
  {
    cry "What a perfect day to die";
  }
}
```

Translates to:

```php
<?php

class Main {
  public function __construct() {
    echo "What a perfect day to die";
  }
}
```

### How many time to do this?

About 30 minutes (?).

### Are you proud of this?

What a shame. Don't tell anybody about that!

### But what is this shit?

It is a small PHP preprocessor, currently able to preprocess only keywords, by usage of directive:

`#keyword mapFrom mapTo`

### Can you give me an example?

Yes, you can fuck with your friends code:

`#keyword true false`

### How to use it?

Call, by command line, the file `PHPP.php` passing your file as argument. You can do that:

`php PHPP.php main.phpp > main.php`
