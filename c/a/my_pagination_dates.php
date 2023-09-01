<?php  
    //pagination

        $search_text_data=$link->query($query_one);

        $count_results=$search_text_data->num_rows;

        // This first query is just to get the total count of rows
        $sql = $query_two;
        $query=$link->query($sql);
        $row = mysqli_fetch_row($query);
        // Here we have the total row count
        $rows = $row[0];
        // This is the number of results we want displayed per page
        $page_rows = $my_num_rows;
        // This tells us the page number of our last page
        $last = ceil($rows/$page_rows);
        // This makes sure $last cannot be less than 1
        if($last < 1){
            $last = 1;
        }
        // Establish the $pagenum variable
        $pagenum = 1;
        // Get pagenum from URL vars if it is present, else it is = 1
        if(isset($_GET['pn'])){
            $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
        }
        // This makes sure the page number isn't below 1, or more than our $last page
        if ($pagenum < 1) { 
            $pagenum = 1; 
        } else if ($pagenum > $last) { 
            $pagenum = $last; 
        }
        // This sets the range of rows to query for the chosen $pagenum
        $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
        // This is your query again, it is for grabbing just one page worth of rows by applying $limit
        $sql = $query_three." $limit";
        $query = mysqli_query($link, $sql);
        // This shows the user what page they are on, and the total number of pages
        $textline1 = "Testimonials (<b>$rows</b>)";
        $textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
        // Establish the $paginationCtrls variable
        $paginationCtrls = '';
        // If there is more than 1 page worth of results
        if($last != 1){
            /* First we check if we are on page one. If we are then we don't need a link to 
               the previous page or the first page so we do nothing. If we aren't then we
               generate links to the first page, and to the previous page. */
            if ($pagenum > 1) {
                $previous = $pagenum - 1;
                $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'&datef='.$datef.'&datet='.$datet.'" ><i class="fa fa-chevron-circle-left"></i></a></li>';
                // Render clickable number links that should appear on the left of the target page number
                for($i = $pagenum-4; $i < $pagenum; $i++){
                    if($i > 0){
                        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&datef='.$datef.'&datet='.$datet.'" >'.$i.'</a></li>';
                    }
                }
            }
            // Render the target page number, but without it being a link
            $paginationCtrls .= '<li class="active"><a>'.$pagenum.'</a></li>';
            // Render clickable number links that should appear on the right of the target page number
            for($i = $pagenum+1; $i <= $last; $i++){
                $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&datef='.$datef.'&datet='.$datet.'" >'.$i.'</a></li>';
                if($i >= $pagenum+4){
                    break;
                }
            }
            // This does the same as above, only checking if we are on the last page, and then generating the "Next"
            if ($pagenum != $last) {
                $next = $pagenum + 1;
                $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'&datef='.$datef.'&datet='.$datet.'" ><i class="fa fa-chevron-circle-right"></i></a></li>';
            }
        }
?>