# gridly

Wordpress plugin to add columns.
Uses OOCSS (https://github.com/stubbornella/oocss).

## usage

There are two* shortcodes, [columns] and [column]. Neither can be empty.

You can nest multiple [column] in a [columns].

[columns] does not allow any attributes.

[column] has the following attributes:

    style
        Inline CSS.
        
    flex
        number of columns to span.