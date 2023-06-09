<?php
    //function for total authors from database
    //the total adjusts base on search and filer
    function countAuthors($conn, $search, $gender, $role){
        
        $search_query = $search != "empty_search" ? $search : ''; //check if the user searched
        $count_sql = "SELECT COUNT(*) FROM table_authors WHERE CONCAT(author_id, author_name, affiliation) ILIKE '%$search_query%'";

        //check if user has filters
        if ($gender !== "empty_gender") {
            $count_sql .= " AND gender = '$gender' ";
        }
        if ($role!== "empty_role") {
            $count_sql .= " AND type_of_author = '$role' ";
        }

        //run query
        $count_result = pg_query($conn, $count_sql);
        $total_items = pg_fetch_result($count_result, 0, 0);

        //return total
        return $total_items;
    }
?>