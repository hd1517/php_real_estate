<?php
/***********************************************
	     Paging Functions
************************************************/

function getPagingQuery($query, $itemPerPage = 10)
{
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}

	// start fetching from this row number
	$offset = ($page - 1) * $itemPerPage;

	return $query . " LIMIT $offset, $itemPerPage";
}


function getPagingLink($query, $itemPerPage = 10, $strGet = '')
{
	global $link;
	$result        = mysqli_query($link, $query);
	$pagingLink    = '';
	$totalResults  = mysqli_num_rows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);

	// how many link pages to show
	$numLinks      = 10;


	// create the paging links only if we have more than one page of results
	if ($totalPages > 1) {

		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;

		if (isset($_GET['properties'])) {
			$strGet .= '&properties& ';
		}

		if (isset($_GET['agentID'])) {
			$urlAgent_id = $_GET['agentID'];
			$strGet .= 'agentID='.$urlAgent_id.'& ';
		}

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}

		// print 'previous' link only if we're not
		// on page one
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <a href=\"$self?page=$page&$strGet/\">Previous</a> ";
			} else {
				$prev = " <a href=\"$self?$strGet\">Previous</a> ";
			}

			$first = " <a href=\"$self?$strGet\">First</a> ";
		} else {
			$prev  = ''; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}

		// print 'next' link only if we're not
		// on the last page
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = " <a href=\"$self?page=$page&$strGet\">Next</a> ";
			$last = " <a href=\"$self?page=$totalPages&$strGet\">Last</a> ";
		} else {
			$next = ''; // we're on the last page, don't show 'next' link
			$last = ''; // nor 'last page' link
		}

		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;

		$end   = min($totalPages, $end);

		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = " <a class=\"currentPage\">$page</a> ";   // no need to create a link to current page
			} else {
				if ($page == 1) {
					$pagingLink[] = " <a href=\"$self?$strGet\">$page</a> ";
				} else {
					$pagingLink[] = " <a href=\"$self?page=$page&$strGet\">$page</a> ";
				}
			}

		}

		$pagingLink = implode(' ', $pagingLink);

		// return the page navigation link
		$pagingLink = $first . $prev . $pagingLink . $next . $last;
	}

	return $pagingLink;
}
?>