<?php
    $page;
    if(!isset($_POST['pageno']))
    $page=1;
    else
    $page=$_POST['pageno'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>moviE4U</title>
        <meta name="viewport" charset="utf-8" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="mainPage.css">
        <link rel="stylesheet" href="movieDetails.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    </head>
    <body class="addToBody">
        <div class="d-flex bg-theme" id="header" style="box-shadow:-2px 2px 5px black; background-color:rgba(128,0,0,0.6);">
            <span class="mr-auto p-2" id="logo">m<i class="fa fa-film bg-theme p-2 rounded-circle"></i>viE4U</span>
            <span class="ml-auto" id="home"><a  href="mainPage.php" class="btn header-link"><i>Home</i></a></span>
        </div>
            <ol start="<?php echo (((int)$GLOBALS['page']-1)*20)+1 ?>" style='background-color:rgba(0,0,0,0.6); box-shadow:-5px -5px 15px darkgray; color:white;' class='p-2 pl-5 m-5'>
                <?php
                    $url='https://api.themoviedb.org/3/discover/movie?api_key=APIKEY&sort_by=';
                    $file=json_decode(file_get_contents($url.$_POST['sortby'].'.desc&vote_count.gte=100&page='.$GLOBALS['page']));
                    // echo '<pre>';
                    // print_r($file);
                    // echo '</pre>';
                    echo    '<div class="sort-header">Top 100 ';
                    if($_POST['sortby']==="popularity")
                    echo 'Popular';
                    else
                    echo 'Rated';
                    echo ' Movies</div>';
                    echo    '<span class="sort-heading">Title</span>'.
                            '<span class="sort-heading" style="float:right;">';
                    if($_POST['sortby']==="popularity")
                    echo 'Popularity';
                    else
                    echo 'Ratings';        
                    echo    '</span>';
                    foreach($file->results as $movie){
                        $content=$_POST['sortby'];
                        echo    '<li class="my-item pt-2 pb-2">'.
                                '<form action="movieDetails.php" method="POST">'.
                                '<input type="hidden" name="id" value="'.$movie->id.'">'.
                                '<button type="submit" class="sort-title">'.$movie->title.' ( '.$movie->release_date.' )'.'<span class="sort-content">'.$movie->$content.'</span></button>'.
                                '</form>'.
                                '</li>';
                    }
                ?>
            </ol>
            <?php
                echo    '<ul class="pagination justify-content-center mt-2">';
                for($i=1;$i<=5;$i++){
                echo    '<li class="page-item sort-text">'.
                        '<form method="POST" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">';
                if($i==$page)
                    echo '<button type="submit" class="page-link myActive">'.$i.'</button>';
                else
                    echo '<button type="submit" class="page-link bg-theme">'.$i.'</button>';
                echo    '<input type="hidden" name="pageno" value="'.$i.'">'.
                        '<input type="hidden" name="sortby" value="'.$_POST['sortby'].'">';
                echo    '</form>'.
                        '</li>';
                }
            ?>

    </body>
</html>