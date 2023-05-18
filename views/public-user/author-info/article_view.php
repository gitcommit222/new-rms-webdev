<link rel="stylesheet" href="../../../views/public-user/articles/article-view.css">
<?php 
    include '../../../includes/admin/templates/header.php';
    include '../../../includes/public-user/templates/user-navbar.php'; 
    include '../../../db/db.php';
    include_once "functionalities/articles-functions.php";
?>

<body>
    <section id="main-content">
        <div class="content-container">
            <?php
                if (isset($_GET['pubID']) && !empty($_GET['pubID'])) {
                    $row = getPublicationData($_GET['pubID'], $conn);
                    if ($row) {
                        displayPublicationData($row, $conn);
                    }
                }
            ?>
        </div>
    </section>   
</body>