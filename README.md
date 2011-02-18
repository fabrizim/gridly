# gridly

Wordpress plugin to add columns.
Uses OOCSS (https://github.com/stubbornella/oocss).

## documentation

There are two shortcodes, *[columns]* and *[column]*. Neither can be empty.

You can nest multiple *[column]* in a *[columns]*.

*[columns]* does not allow any attributes.

*[column]* has the following attributes:


* _style_ Inline CSS.
        
* _flex_ number of columns to span (default is 1)

\* These shortcodes can be nested as long as you append  \_# after the code where # is the nesting level - see the example for clarification.

## example

    [columns]
        [column flex=2]Column One (2/5)[/column]
        [column flex=3]Column Two (3/5)[/column]
    [/columns]
    
Will produce this code:
    
    <div class="line gridly">
        <div class="unit size2of5">Column One (2/5)</div>
        <div class="unit size3of5 lastUnit">Column Two (3/5)</div>
    </div>

An example of nested tags:
    [columns]
        [column flex=1]Column A. [/column]
        [column flex=3]
            [columns_1]
                [column_1]Column B.1.[/column_1]
                [column_1]Column B.2.[/column_1]
            [/columns_1]
        [/column]
    [/columns]
    
Will produce this code:
    
    <div class="line gridly">
        <div class="unit size1of4">Column A.</div>
        <div class="unit size3of4 lastUnit">
            <div class="line gridly">
                <div class="unit size1of2">Column B.1.</div>
                <div class="unit size1of2 lastUnit">Column B.2.</div>
            </div>
        </div>
    </div>