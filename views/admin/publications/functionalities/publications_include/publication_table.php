<?php
    require_once('functionalities/publication-get-info.php');
    require_once('functionalities/publications_include/publication_count.php');

    $additionalQuery = authorSearch($conn, $search);
    $table_rows = get_data($conn, $additionalQuery, $search, $type, $fund, $year, $page_number);
    $total_records = countPublications($conn, $additionalQuery, $search, $type, $fund, $year );
?>

<table>
    <thead>
        <tr>
            <th class="col-title" style="min-width: 350px;">Title</th>
            <th class="col-type">Type</th>
            <th class="col-publisher" style="min-width: 190px;">Publisher</th>
            <th class="col-research-area">Research Area</th>
            <th class="col-college">College</th>
            <th class="col-quartile">Quartile</th>
            <th class="col-campus">Campus</th>
            <th class="col-sdg">SDG's</th>
            <th class="col-date-published">Date Published</th>
            <th class="col-authors" style="min-width: 190px;">Authors</th>
            <th class="col-funding">Funding</th>
            <th class="col-fund-type">Fund Type</th>
            <th class="col-fund-agency">Fund Source</th>
            <th class="col-citations">Citations</th>
            <th class='stickey-col-header' style="background-color: var(--grey);">Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <?php
        
        if ($table_rows !== null) {
        foreach ($table_rows as $row) {
        ?>
        <tr>
            <td class="publication-col col-title" style="min-width: 15.625rem"><?=$row['title_of_paper'];?></td>
            <td class="publication-col col-type"><?=$row['type_of_publication'] != null ? $row['type_of_publication'] : "N/A";?></td>
            <td class="publication-col col-publisher"><?=$row['publisher'] != null ? $row['publisher'] : "N/A";?></td>
            <td class="publication-col col-research-area"><?=$row['department'] != null ? $row['department'] : "N/A";?></td>
            <td class="publication-col col-college"><?=$row['college'] != null ? $row['college'] : "N/A";?></td>
            <td class="publication-col col-quartile"><?=$row['quartile'] != null ? $row['quartile'] : "N/A";?></td>
            <td class="publication-col col-campus"><?=$row['campus'] != null ? $row['campus'] : "N/A";?></td>
            <td class="publication-col col-sdg"><?=$row['sdg_no'] != null ? $row['sdg_no'] : "N/A";?></td>
            <td class="publication-col col-date-published"><?=$row['date_published'] != null ? $row['date_published'] : "N/A";?></td>
            <td class="publication-col col-authors"><?=$row['authors'] != null ? $row['authors'] : "N/A";?></td>  
            <td class="publication-col col-funding"><?=$row['nature_of_funding'] != null ? $row['nature_of_funding'] : "N/A";?></td>
            <td class="publication-col col-fund-type"><?=$row['funding_type'] != null ? $row['funding_type'] : "N/A";?></td>
            <td class="publication-col col-fund-agency"><?=$row['funding_source'] != null ? $row['funding_source'] : "N/A";?></td>
            <td class="publication-col col-citations"><?=$row['number_of_citation']!= null ? $row['number_of_citation'] : "N/A";?></td>

            <td class='pb-action-btns stickey-col'>
            <?php
                $google_link = $row['google_scholar_details'];
                if (empty($google_link)){
                        $google_link = null;
                    }
            ?>
            <!-- Open certificate in a new tab-->
                <a onclick="redirect('<?=$google_link != null? $google_link : 'no_url';?>')" class="gdrive-btn">
                        <i class="fa-solid fa-arrow-up-right-from-square icon"></i>
                </a>
                <form action="edit-publication.php" method="POST">
                    <input type="hidden" name="row_id" value="<?=$row['publication_id']?>">
                    <button type="submit" class="edit-btn" name="edit"><i class="fa-solid fa-pen-to-square icon"></i></button>
                </form>      
                <button class="delete-btn" name="delete" onclick="confirmDelete('<?=$row['publication_id']?>')"><i class="fa-solid fa-trash-can"></i></button>                   
            </td>
    </tr>
    <?php
    }
}else{
    ?>
    <tr>
        <td colspan='15' style="text-align:center">No Records Found!</td>
    </tr>
<?php
}
?>
    </tbody>
</table>