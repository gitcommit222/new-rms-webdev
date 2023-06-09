<?php
// getting the total number of users account
function getUserCount($conn, $reuse_stmt = false) {
    $query = "SELECT user_id FROM table_user;";
    if (!$reuse_stmt) {
        $stmt = pg_prepare($conn, "get_user_id", $query);
    }
    $result = pg_execute($conn, "get_user_id", array());
    if (!$result) {
        echo "Query failed: " . pg_last_error($conn);
    } else {
        $row_count = pg_num_rows($result);
        if ($row_count == 0) {
            return '0';
        } else {
            return $row_count;
        }
    }
}
// getting the total number of authors 
function getAuthorCount($conn, $reuse_stmt = false) {
    $query = "SELECT author_id FROM table_authors;";
    if (!$reuse_stmt) {
        $stmt = pg_prepare($conn, "get_author_id", $query);
    }
    $result = pg_execute($conn, "get_author_id", array());
    if (!$result) {
        echo "Query failed: " . pg_last_error($conn);
    } else {
        $row_count = pg_num_rows($result);
        if ($row_count == 0) {
            return '0';
        } else {
            return $row_count;
        }
    }
}
// getting the total number of articles in publications
function getArticleCount($conn, $reuse_stmt = false) {
    $query = "SELECT title_of_paper FROM table_publications;";
    if (!$reuse_stmt) {
        $stmt = pg_prepare($conn, "get_title_of_paper", $query);
    }
    $result = pg_execute($conn, "get_title_of_paper", array());
    if (!$result) {
        echo "Query failed: " . pg_last_error($conn);
    } else {
        $row_count = pg_num_rows($result);
        if ($row_count == 0) {
            return '0';
        } else {
            return $row_count;
        }
    }
}
// getting the number o authors with most contributions in publications
function getPublicationsContributors($conn) {
    $sql = "SELECT * FROM table_authors ORDER BY author_id ASC";
    $result = pg_query($conn, $sql);

    if(pg_num_rows($result) > 0){
        $contributors = array();
        while ($row = pg_fetch_assoc($result)) {
            $count1=0;
            $getAuthors = "SELECT authors FROM table_publications";
            $getAuthorsResult = pg_query($conn, $getAuthors);

            if(pg_num_rows($getAuthorsResult) > 0){
                while ($row2 = pg_fetch_assoc($getAuthorsResult)) {
                    $authorIds = explode(',', $row2['authors']);
                    foreach ($authorIds as $id) {
                        if ($id === $row['author_id'] ){
                            $count1=$count1+1;
                        }
                    }
                }
            }
    
            $total_count = $count1;
            if ($total_count > 0) {
                $contributors[] = array(
                    'author_name' => $row['author_name'],
                    'total_publications' => $total_count
                );
            }
        }

        // Sort contributors by number of publications in descending order
        usort($contributors, function($a, $b) {
            return $b['total_publications'] - $a['total_publications'];
        });

        // Display top 9 contributors
        $count = 0;
        ?>
        <table>
        <tr>
            <th>Authors</th>
            <th>Number of Publications</th>
        </tr>
        <?php
        foreach ($contributors as $contributor) {
            $count++;
            if ($count > 9) {
                break;
            }
            ?>
            <tr>
                <td><?=$contributor['author_name'];?></td>
                <td><?=$contributor['total_publications'];?></td>
            </tr>
            <?php
        }
    }
    ?>
    </table>
    <?php
}
// getting the number of authors with most contributions in ip assets
function getIpAssetsContributors($conn) {
    $sql = "SELECT * FROM table_authors ORDER BY author_id ASC";
    $result = pg_query($conn, $sql);

    if(pg_num_rows($result) > 0){
        $contributors = array();
        while ($row = pg_fetch_assoc($result)) {

            $count2=0;
            $getAuthors = "SELECT authors FROM table_ipassets";
            $getAuthorsResult = pg_query($conn, $getAuthors);

            if(pg_num_rows($getAuthorsResult) > 0){
                while ($row2 = pg_fetch_assoc($getAuthorsResult)) {
                    $authorIds = explode(',', $row2['authors']);
                    foreach ($authorIds as $id) {
                        if ($id === $row['author_id'] ){
                            $count2=$count2+1;
                        }
                    }
                }
            }

   
            $total_count = $count2;
            if ($total_count > 0) {
                $contributors[] = array(
                    'author_name' => $row['author_name'],
                    'total_publications' => $total_count
                );
            }
        }

        // Sort contributors by number of publications in descending order
        usort($contributors, function($a, $b) {
            return $b['total_publications'] - $a['total_publications'];
        });

        // Display top 9 contributors
        $count = 0;
        ?>
        <table>
        <tr>
            <th>Authors</th>
            <th>Number of Publications</th>
        </tr>
        <?php
        foreach ($contributors as $contributor) {
            $count++;
            if ($count > 9) {
                break;
            }
            ?>
            <tr>
                <td><?=$contributor['author_name'];?></td>
                <td><?=$contributor['total_publications'];?></td>
            </tr>
            <?php
        }
    }
    ?>
    </table>
    <?php
}
// getting the most cited articles in publications
function getMostViewedPapers($conn, $reuse_stmt = false) {
    $sql = "SELECT title_of_paper, number_of_citation FROM table_publications WHERE number_of_citation IS NOT NULL ORDER BY number_of_citation DESC LIMIT 4;";
    if (!$reuse_stmt) {
        $stmt = pg_prepare($conn, "get_most_viewed_papers", $sql);
    }
    $result = pg_execute($conn, "get_most_viewed_papers", array());

    $output = "<table>";
    $output .= "<tr><th>Title of Paper</th><th>Number of Citations</th></tr>";
    while ($row = pg_fetch_assoc($result)) {
        $output .= "<tr><td>".$row['title_of_paper']."</td><td>".$row['number_of_citation']."</td></tr>";
    }
    $output .= "</table>";

    return $output;
}
// getting the number of published articles
function getPublishedIPAssets($conn) {
    $query = "SELECT title_of_work FROM table_ipassets WHERE status = $1";
    $params = array("published");

    $query_run = pg_prepare($conn, "pub_query", $query);
    if(!$query_run) {
        echo "Prepared statement creation failed: " . pg_last_error($conn);
    } else {
        $result = pg_execute($conn, "pub_query", $params);
        if(!$result) {
            echo "Query execution failed: " . pg_last_error($conn);
        } else {
            $row_count = pg_num_rows($result);
            if($row_count == 0) {
                return '0';
            } else {
                return $row_count;
            }
        }
    }
}
// getting the recently added articles
function getRecentIpAssets($conn, $limit) {
    $query = "SELECT title_of_work, date_registered FROM table_ipassets WHERE date_registered IS NOT NULL ORDER BY date_registered DESC LIMIT $1";
    $params = array($limit);

    $query_run = pg_prepare($conn, "recent_assets_query", $query);
    if(!$query_run) {
        echo "Prepared statement creation failed: " . pg_last_error($conn);
    } else {
        $result = pg_execute($conn, "recent_assets_query", $params);
        if(!$result) {
            echo "Query execution failed: " . pg_last_error($conn);
        } else {
            $rows = pg_fetch_all($result);
            if(!$rows) {
                echo "<p>No data found</p>";
            } else {
                echo "<table>";
                echo "<tr><th>Title</th><th>Date Registered </th></tr>";    
                foreach($rows as $row) {
                    $date = date('F d, Y', strtotime($row['date_registered']));
                    echo "<tr><td>".$row['title_of_work']."</td><td>".$date."</td></tr>";
                }
                echo "</table>";
            }
        }
    }
}
// getting the ip assets per campus
function getIpAssetsCampus($conn) {
    $query = "SELECT campus, COUNT(*) as dataset FROM table_ipassets WHERE campus IS NOT NULL GROUP BY campus";
    $result = pg_query($conn, $query);

    $data = array();
    $labels = array();
    while ($row = pg_fetch_assoc($result)) {
        $labels[] = $row["campus"];
        $data[] = intval($row["dataset"]);
    }

    return array(
        "data" => json_encode($data),
        "labels" => json_encode($labels)
    );
}
// getting the type of publication in table publication
function getPublicationType($conn){
    $query = "SELECT type_of_publication, COUNT(*) as dataset FROM table_publications WHERE type_of_publication is NOT NULL GROUP BY type_of_publication";
    $result = pg_query($conn, $query);

    $data = array();
    $labels = array();
    while ($row = pg_fetch_assoc($result)) {
        $labels[] = $row["type_of_publication"];
        $data[] = intval($row["dataset"]);
    }

    return array(
        "data" => json_encode($data),
        "labels" => json_encode($labels)
    );

}
// getting the number ip assets per year
function getIPAssetsPerYear($conn) {
    $query = "SELECT EXTRACT(YEAR FROM date_registered) AS year, COUNT(*) AS count
    FROM table_ipassets
    WHERE date_registered IS NOT NULL
    GROUP BY EXTRACT(YEAR FROM date_registered)
    ORDER BY EXTRACT(YEAR FROM date_registered) ASC;
    ";
    $result = pg_query($conn, $query);

    $year = array();
    $year_data = array();

    if (pg_num_rows($result) > 0) {
        while($data = pg_fetch_array($result)){
            $year[] = $data['year'];
            $year_data[] = intval($data['count']);
        }
    }

    $ipyear_data = json_encode($year_data);
    $ipyear_labels = json_encode($year);

    return array(
        'data' => $ipyear_data,
        'labels' => $ipyear_labels
    );
}
// getting the number of publicatons per year 
function getPublicationsPerYear($conn) {
    $query = "SELECT EXTRACT(YEAR FROM date_published) AS year, COUNT(*) AS count
    FROM table_publications
    WHERE date_published IS NOT NULL
    GROUP BY EXTRACT(YEAR FROM date_published)
    ORDER BY EXTRACT(YEAR FROM date_published) ASC;
    ";
    $result = pg_query($conn, $query);

    $year = array();
    $year_data = array();

    if (pg_num_rows($result) > 0) {
        while($data = pg_fetch_array($result)){
            $year[] = $data['year'];
            $year_data[] = intval($data['count']);
        }
    }

    $publications_year = json_encode($year);
    $publications_data = json_encode($year_data);

    return array(
        'data' => $publications_data,
        'labels' => $publications_year
    );
}
?>