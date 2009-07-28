<?PHP

error_reporting(E_ALL);
// $Id$

/**
 * DpTable Class.
 *
 *
 *  <p>OO table display class.</p>
 *
 *  <p>Initialize with a result-set-type array.</p>
 * </p>Use the AddColumn method to accumulate column information.
 *  <p>Sorting. We have a choice of sorting in the query, or sorting the array.
Sorting in the query might be marginally quicker, but it means contorting the
SQL statement, which increases coupling, and makes the query more complicated
(bad) and less general and reusable (bad). So we'll do sorting in the array.

This also anticipates adding report manipulation to the client,
as JavaScript becomes more standard (e.g. Ajax, DHTML, etc.)
 */

class DpTable
{
    var $_table_caption;
    var $_align;
    var $_sortcol;
    var $_captions   = array() ;
    var $_columns    = array() ;
    var $_rows       = array() ;  // Array for rows e.g. resultset.
    var $_sort_func ;
    var $_style_id ;
    var $_page_number ;
    var $_rows_per_page ;

    /**
     * Constructor.
     *
     */

    function DpTable( $pagenumber = 1, $rowsperpage = 0 ) {
        $this->_page_number     = $pagenumber ;
        $this->_rows_per_page   = $rowsperpage ;
    }

    /**
     * setRows. Argument is resultset-type array (colname => value).
     * 
     */

    function SetRows($rows) {
        $this->_rows = $rows;
    }

    /**
     * addColumn.  
     * 
     * 
     */

    function addColumn( 
                        $caption,
                        $colname,
                        $template = null,
                        $styleid = null) {
        $this->_columns[] = new DpTableColumn(
                        $caption, 
                        $colname,
                        $template,
                        $styleid);
    }

    function SetStyle($styleid) {
        $this->_style_id = $styleid;
    }

    /**
     * setSort. Arguments: Column name, and A or D.
     * Used to indicate, when a column heading is clicked,
     * which sort direction should be specified.
     *
     */

    function SetSort($sortcol, $sortfunc) {
        $this->_sortcol = $sortcol ;
        $this->_sortfunct = $sortfunc ;
    }

    /**
     * echo. Generate the report.
     *
     */

    function EchoTableNumbered() {
        $this->EchoTable(true) ;
    }

    /**
     * addCaption. Add a superheader above the header row.
     *
     */

    function AddCaption( $text, $ncol = 1 ) {
        $this->_captions[] = new DpTableCaption( $text, $ncol ) ;
    }

    function GetSort() {
        foreach( $this->_columns as $col ) {
            
        }
    }

    function RowsPerPage() {
        return $this->_rows_per_page ;
    }

    function PageNumber() {
        return $this->_page_number ;
    }

    function EchoTable($isnumbered = false) {
        echo "<table cellpadding='3' border='1' class='dptable'>\n";

        if(! empty($this->_table_caption)) {
            echo "<caption>$this->_table_caption</caption>\n";
        }
        echo "<tbody>\n";
        $this->_echo_captions($isnumbered);

        $odd_even = true ;
        $i = ( $this->_page_number - 1 ) * $this->_rows_per_page ;

        foreach($this->_rows as $row) {
            $i++ ;
            $odd_even = !$odd_even;
            // }
            if($odd_even)
                echo "<tr class='odd'>\n";
            else
                echo "<tr>\n";
            if($isnumbered) {
                echo "<td>$i</td>\n";
            }
            foreach($this->_columns as $col) {
                $col->EchoCell($row);
            }
            echo "</tr>\n";
        }
        echo "</tbody>
            </table>\n";
        if( $this->_rows_per_page > 0 )
            $this->_echo_pager() ;
    }

    /**
     * _echo_captions.
     *
     */

    function _echo_captions($isnumbered) {
        if( count( $this->_captions ) > 0 ) {
            echo "<tr>\n";
            if( $isnumbered ) {
                echo "<th> &nbsp;</th>\n";
            }
            foreach( $this->_captions as $cap ) {
                $cap->EchoCaption() ;
            }
            echo "</tr>\n";
        }
                
            
        echo "<tr>\n";
        if($isnumbered)
            echo "<th> &nbsp;</th>\n";
        foreach($this->_columns as $col) {
            $col->echoCaption($this->_sortcol == $col->colName());
        }
        echo "</tr>\n";
    }

    /**
     * _echo_row($row).
     *
     */

    function _echo_row($row, $rownum = 0) {
        echo "<tr>\n";
        if($rownum)
            echo "<td>$rownum</td>\n";
        foreach($this->_columns as $col) {
            $col->EchoCell($row);
        }
        echo "</tr>\n";
    }

    function _page_number() {
        return $this->_page_number;
    }

    function _echo_pager() {
        // echo "<center>\n" ;
    }
}   // end class DpTable

///////////////////////////////////////////////////////////////////////

/**
     * DpTableColumn class.
     */

///////////////////////////////////////////////////////////////////////

class DpTableCaption
{
    var $_caption;
    var $_colcount ;
    var $_align;
    var $_sort;

    function DpTableCaption( $caption, $colcount = 1 ) {
        $this->_caption = $caption ;
        $this->_colcount = $colcount ;
        $this->_set_align( $caption ) ;
    }

    // 0 = none, 1 = asc, 2 = desc
    function PingSort() {
        if( $this->_sort == 2 )
            $this->_sort = 0 ;
        else
            $this->sort++ ;
    }

    function _set_align($cap) {
        switch($cap[0]) {
            default :
                $this->_align = "" ;
                $this->_caption = $cap ;
                return ;
            case '<' :
                $this->_align = " style='text-align: left'";
                break ;
            case '>' :
                $this->_align = " style='text-align: right'";
                break ;
            case '^' :
                $this->_align = " style='text-align: center'";
                break ;
        }
        $this->_caption = substr( $cap, 1 ) ;
    }

    function EchoCaption() {
        echo "<th$this->_align" ;
        if( $this->_colcount > 1 ) {
            echo " colspan='{$this->_colcount}'";
        }
        echo ">{$this->_caption}</th>\n";
    }
}

class DpTableColumn
{
    var $_caption;
    var $_colname;
    var $_template;
    var $_sortcol;
    var $_style_id = null;
    var $_issort;
    var $_align;

    /**
        Initializer.

        caption is text column heading.
        colname is the name of the field in the resultset row.
        template is one of:
            a. empty - the cell value is echoed.
            b. an "sprintf" format string, optionally including "%s" for the
                cell value.
            c. if colname is empty, a function to echo any string derivable from the $row. Notice
               a colname for the column is redundant.
        styleid is the name of the css style to use.
        Public.
     */

    function DpTableColumn(
                $caption, 
                $colname, 
                $template = null, 
                $styleid = null) {
        $this->_colname = $colname;
        $this->_template = $template;
        $this->_style_id = $styleid;

        $this->_set_align($caption) ;
    }

    function _set_align($cap) {
        switch($cap[0]) {
            default :
                $this->_align = "" ;
                $this->_caption = $cap ;
                return ;
            case '<' :
                $this->_align = " style='text-align: left'";
                break ;
            case '>' :
                $this->_align = " style='text-align: right'";
                break ;
            case '^' :
                $this->_align = " style='text-align: center'";
                break ;
        }
        $this->_caption = substr( $cap, 1 ) ;
    }

    function EchoCaption() {

        echo "<th$this->_align> $this->_caption</th>\n";
    }

    function EchoCell($row) {
        $cname = $this->_colname;
        if(!empty($cname)) {
            if(is_callable($this->_template)) {
                $str = call_user_func($this->_template, $row[$cname]);
            }
            elseif(!empty($this->_template))
                $str = sprintf($this->_template, $row[$cname]);
            else {
                $str = $row[$cname];
            }
        }
        else {
            // template function on entire row if colname is blank.
            if(is_callable($this->_template)) {
                $str = call_user_func($this->_template, $row);
            }
            else
                $str = "Template Error - " . $this->_template;
        }
        echo "<td$this->_align>$str</td>\n";
    }

    function ColName() {
        return $this->_colname;
    }
} // end DpTableColumn

?>
