<?PHP

// There are two different ways of counting saved-as-done pages in the DP code:
//
// 1) As each page is saved, increment a counter associated with the user who
//    saved the page (and decrement it if the page is un-saved).
// 2) Look in each projectID* table for pages saved in a given interval.
//
// Lacking concise terminology for this distinction, I propose the term
// "page tally" for (1), and leave "page count" for (2).
//
// This file is for code dealing with page tallies.

?>
