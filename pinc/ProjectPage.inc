<?php
include_once($relPath.'RoundDescriptor.inc');

class NonexistentPageException extends Exception
{
}

// includes only relevant details
class ProjectPage
{
    public function __construct($projectid, $page_name)
    {
        $sql = sprintf("
            SELECT state
            FROM $projectid
            WHERE image = '%s'
        ",
            DPDatabase::escape($page_name)
        );
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);
        if (!$row) {
            throw new NonexistentPageException(sprintf(_("There is no page named '%s'"), $page_name));
        }
        $this->name = $page_name;
        $this->state = $row["state"];
        $this->projectid = $projectid;
        $this->round = get_Round_for_page_state($this->state);
    }

    public function validate_user()
    {
        global $pguser;

        $sql = sprintf("
            SELECT {$this->round->user_column_name}
            FROM $this->projectid
            WHERE image='%s'
        ",
            DPDatabase::escape($this->name)
        );
        $res = DPDatabase::query($sql);
        $user = mysqli_fetch_row($res)[0];
        if ($user !== $pguser) {
            $err = sprintf(
                _('You (%1$s) do not have the necessary access to page %2$s'),
                $pguser,
                $page_name
            );
            throw new BadRequest($err);
        }
    }

    public function check_page_out_or_temp()
    {
        $round = $this->round;
        if (!in_array($this->state, [$round->page_out_state, $round->page_temp_state])) {
            throw new BadRequest("Bad page state");
        }
    }
}
