<?php
include_once($relPath.'misc.inc');

// Returns the special color associated with this
// project, or null if no such color is specified.
//
// $project is supposed to be an associative array
// representing a  record from the
// projects table. At the moment it is 'enough'
// that the keys 'comments' and 'username' are
// existant.
function get_special_color_for_project($project) {

    $bgcolor = null;

    // will be nice one day to get all the following out of a table perhaps?

    // default Special colour (will be over-ridden by any specific matches below)

    if ( startswith( $project['comments'], 'SPECIAL:' ) )
    {
        $bgcolor = '#FFFF66'; // fadedyellow
    }

    // Halloween colors for Halloween projects!
    if ( startswith( $project['comments'], 'Halloween' ) )
    {
        $bgcolor = 'orange'; // #FF9900
    }
    // Wanted to do orange-on-black, too,
    // but couldn't figure out how to make link-text orange,
    // and it was pretty unreadable as is on a black background.

    // green for Ramadan, St Patrick
    if ( startswith( $project['comments'], 'SPECIAL: Ramadan' )
         or startswith( $project['comments'], 'SPECIAL: St. Pat') )
    {
        $bgcolor = '#00FF00';
    }
    // mid green for India & George Sand
    if (  startswith( $project['comments'], 'SPECIAL: India') 
         or ($project['username'] == 'PM-Sand') )
    {
        $bgcolor = '#33FF99';
    }

    // khaki for Boer War
    if (startswith( $project['comments'], 'SPECIAL: Boer'))
    {
        $bgcolor = '#D7CDA4';
    }

    // blue for Burns' night (Scotland)
    if (startswith( $project['comments'], 'SPECIAL: Burns'))
    {
        $bgcolor = '#99CCFF';
    }

    // poppy red for Armistice Day, pale red for Christmas
    if ( startswith( $project['comments'], 'SPECIAL: Nov 11' ) or
         startswith( $project['comments'], 'SPECIAL: CHRISTMAS' ) or
         startswith( $project['comments'], 'SPECIAL: Christmas' ) or
         startswith( $project['comments'], 'SPECIAL: WWI' ) or
         startswith( $project['comments'], 'SPECIAL: Ides' ))
    {
        $bgcolor = '#FF9090';
    }

    // Earth blue for Earth Day
    if ( startswith( $project['comments'], 'SPECIAL: Earth' ) or
         startswith( $project['comments'], 'SPECIAL: DP-5000' ))
    {
        $bgcolor = '#669CFF';
    }

    // very light blue for birthday books before or after the day
    if ( startswith( $project['comments'], 'SPECIAL: Birthday' ) )
    {
        $bgcolor = '#CCFFFF';
    }


     // slightly richer blue for birthday books when today IS the day
    $bday = date('md');
    if ( startswith( $project['comments'], "SPECIAL: Birthday $bday" ) )
    {
        $bgcolor = '#33CCFF';
    }

    // grey for Native American Heritage Month
    if ( startswith( $project['comments'], 'SPECIAL: NAHM' ) )
    {
        $bgcolor = '#CCCCCC';
    }


    // green for World Health Day
    if ( startswith( $project['comments'], 'SPECIAL: Health' ) )
    {
        $bgcolor = '#00FF00';
    }

    // yellow for Children's Book Week - actually Blue so as not to clash with other special
    if ( startswith( $project['comments'], 'SPECIAL: CBW' )
        OR startswith( $project['comments'], 'SPECIAL: ChBook'))
    {
        $bgcolor = '#0099FF';
        //$bgcolor = '#FFFF33';
    }

    // nice green for Abolition of Slavery
    if ( startswith( $project['comments'], 'SPECIAL: Abolition' )
        OR startswith( $project['comments'], 'SPECIAL: IWD'))
    {
        $bgcolor = '#00CC99';
    }

    // blue for Wright Bros
    if ( startswith( $project['comments'], 'SPECIAL: FLY100' ) )
    {
        $bgcolor = '#0099FF';
    }

    // middle grey for Black History Month (and Slave Narratives - temp)
    if ( startswith( $project['comments'], 'SPECIAL: BHM' ))
    {
        $bgcolor = '#D6D6D6';
    }

    // pink for Saint Valentine's Day
    if ( startswith( $project['comments'], 'SPECIAL: St. Val' ) or
        startswith( $project['comments'], 'SPECIAL: April Fool') )
    {
        $bgcolor = '#FF99FF';
    }


    // purple:    #9966CC
    // gold:      #FFCC0E
    // scotsblue: #0090FE

    return $bgcolor;
}
?>