<?php
/**
 * @package Gridly
 * @version 1.0.0
 */
/*
Plugin Name: Gridly
Plugin URI: http://owlwatch.com/
Description: Gridly lets you add grid columns with simple short code [columns] and [column]. It uses the grids.css from OOCSS
Author: Mark Fabrizio
Version: 1.0.1
Author URI: http://owlwatch.com
*/

// add the css
if( !is_admin() )
{
    wp_enqueue_style('gridly',  plugins_url('/gridly.css', __FILE__ ));
}

add_shortcode('gridly', array('Gridly_Shortcode', 'run'));
add_shortcode('columns', array('Gridly_Shortcode', 'run'));
class Gridly_Shortcode
{
    
    protected $level = 0;
    protected $total = 0;
    protected $columns = array();
    
    public static function run($attrs, $content, $tag)
    {
        $instance = new self($attrs, $content);
        return $instance->replace();
    }
    
    public function __construct($attrs, $content, $level=0)
    {
        $this->attrs = $attrs;
        $this->content = $content;
        $this->level = $level;
    }
    
    public function replace()
    {
        
        
        $col_callback = array(&$this, 'column');
        $cols_callback = array(&$this, 'columns');
        
        $col_shortcode = 'column'.($this->level == 0 ? '' : ('_'.$this->level));
        $cols_shortcode = 'columns_'.($this->level+1);
        
        // replace paragraphs added before and after tags
        $this->content = preg_replace('#(<p>)?\[(\/?)([a-zA-Z_]+)([^\\]]*)\](<\/p>)?#i', '[${2}${3}${4}]', $this->content);
        
        add_shortcode($col_shortcode, $col_callback);
        add_shortcode($cols_shortcode, $cols_callback);
        do_shortcode($this->content);
        
        foreach( $this->columns as $index => &$column ){
            $column = preg_replace('#__total__#', $this->total, $column);
            $lastUnit = ($index == count( $this->columns)-1 ) ? ' lastUnit' : '';
            $column = preg_replace('#__lastUnit__#', $lastUnit, $column);
            $column = do_shortcode( $column );
        }
        
        $this->content = implode("\n",$this->columns);
        
        remove_shortcode($col_shortcode);
        remove_shortcode($cols_shortcode);
        return '<div class="line gridly">'.$this->content.'</div>';
    }
    
    public function columns($attrs, $content)
    {
        $gridly = new self($attrs,$content,$this->level+1);
        return $gridly->replace();
    }
    
    public function column($attrs, $content)
    {
        $flex = @$attrs['flex'] ? (int)$attrs['flex'] : 1;
        $this->total += $flex;
        
        $style = @$attrs['style'] ? (string)$attrs['style'] : false;
        if( $style ) $style = 'style="'.$style.'"';
        
        // lets strip breaks at the top and bottom of the column (and make them paragraphs?)
        $content = preg_replace('#^\s*<br\s*/?>#m', '<p>', $content );
        $content = preg_replace('#<br\s*/?>\s*$#m', '</p>', $content );
        $this->columns[] = '<div class="unit size'.$flex.'of__total____lastUnit__">'.
                                '<div class="column-padding" '.$style.'>'.$content.'</div>'.
                            '</div>';
        return ''; // we'll put the pieces together after.
    }
}