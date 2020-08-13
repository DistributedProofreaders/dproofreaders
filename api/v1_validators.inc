<?php
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');

//===========================================================================
// Validators

function validate_round($roundid)
{
    global $Round_for_round_id_;

    if(!in_array($roundid, array_keys($Round_for_round_id_)))
    {
        throw new NotFoundError("Invalid round");
    }
    return $Round_for_round_id_[$roundid];
}

function validate_project($projectid)
{
    // validate and load the specified projectid
    try
    {
        return new Project($projectid);
    }
    catch(NonexistentProjectException $exception)
    {
        throw new NotFoundError("No such project ID");
    }
}

function validate_wordlist($wordlist)
{
    if(!in_array($wordlist, ["good", "bad"]))
    {
        throw new NotFoundError();
    }
    return $wordlist;
}
