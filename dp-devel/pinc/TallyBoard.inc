<?PHP

// A "tally" is simply a running count of some specified quantity for
// some specified agent (e.g., a user or team), called the tally's 'holder'.
// (E.g., the holder could be user 23, and the quantity could be
// pages saved-as-done in P1 or P2.)

// A holder is identified by a 'holder_type' (e.g., 'U' for user)
// and an integer holder_id (e.g. 23).

// A tally quantity is identified by a 'tally_name'
// (e.g. 'P' for pages saved-as-done in P1 or P2).

// This file will attempt to encapsulate all access to the tables that
// hold tally data.

// -----------------------------------------------------------------------------

// A TallyBoard maintains all tally data (past and present)
// for a particular combination of tally_name and holder_type.

class TallyBoard
{
    function TallyBoard( $tally_name, $holder_type )
    {
        $this->tally_name  = $tally_name;
        $this->holder_type = $holder_type;
    }

}

// vim: sw=4 ts=4 expandtab
?>
