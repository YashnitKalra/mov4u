<!DOCTYPE html>
<html>
    <head>
        <title>moviE</title>
        <meta name="viewport" charset="utf-8" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="mainPage.css">
        <link rel="stylesheet" href="movieDetails.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    </head>
    <body>
            <div class="d-flex bg-theme" id="header" style="box-shadow:-2px 2px 5px black; background-color:rgba(128,0,0,0.6);">
                <span class="mr-auto p-2" id="logo">m<i class="fa fa-film bg-theme p-2 rounded-circle"></i>viE4U</span>
                <form method="POST" action="sortedMovies.php">
                    <input type="hidden" name="sortby" value="vote_average">
                    <button type="submit" class="btn btn-outline-light">Top Rated</button>
                </form>
                <form method="POST" action="sortedMovies.php" class="ml-1">
                    <input type="hidden" name="sortby" value="popularity">
                    <button type="submit" class="btn btn-outline-light">Most Popular</button>
                </form>
            </div>
            <form class="mt-5 mb-5" style="position:sticky; top:0; z-index:10;" method="GET" action="<?php echo $_SERVER['PHP_SELF']?>">
                <div class="input-group input-group-lg w-75 m-auto" style="box-shadow:-5px 5px 15px black;">
                    <div class="input-group-prepend"><span class="input-group-text fa fa-search"></span></div>
                    <input type="text" placeholder="Search Movie, TV Series" class="form-control" name="searchInput">
                    <input type="hidden" name="pageno" value="1">
                    <input type="submit" class="btn bg-theme">
                </div>
            </form>
                <?php
                    $imageUrl='https://image.tmdb.org/t/p/w500';
                    if(isset($_GET['searchInput']) && !empty($_GET['searchInput'])){
                        echo '<script>$("body").addClass("addToBody");</script>';
                        $url='https://api.themoviedb.org/3/search/movie?api_key=APIKEY&query=';
                        $name=$_GET['searchInput'];
                        $page=$_GET['pageno'];
                        $name = str_replace(' ','+',trim($name));
                        $file=json_decode(file_get_contents($url.$name.'&page='.$page));
                        if($file->total_results==0)
                        echo '<h2 class="ml-5">No match found</h2>';
                        else{
                            // echo"<pre style='color:white;'>";
                            // print_r($file);
                            // echo"</pre>";
                        $SEARCH=$file->results;
                        $num=0;
                        echo '<div class="bg-theme p-5 m-auto" style="background-color:rgba(128,0,0,0.3); width:80%; border-radius:50px; position:relative;">';
                        foreach($SEARCH as $s){
                            $image=$imageUrl.$s->poster_path;
                            if($num%4===0)
                                echo '<div class="row">';     
                            echo "<div class='col-sm-3 p-3 pt-5'>";
                            echo    '<div class="movie-border" id="'.$s->id.'">';
                                    if(isset($s->poster_path))
                                    echo '<img src="'.$image.'" class="inner" style="position:absolute; height:100%; width:100%; display:block;">';
                                    else
                                    echo '<h4 class="inner" style="display:inline;">Poster not available</h4>';
                            echo    '<div class="container-fluid movie-rating" id="'.$s->id.'rating" style="position:absolute; top:10%; text-align:center; display:none;">'.
                                    '<i class="fa fa-star text-warning"></i><br>'.$s->vote_average.'/10'.
                                    '</div>'.
                                    '<div class="container-fluid movie-release" id="'.$s->id.'released" style="position:absolute; bottom:10%; text-align:center; display:none;">'.
                                    $s->release_date.
                                    '<br><form method="POST" action="movieDetails.php">'.
                                    '<input type="hidden" name="id" value="'.$s->id.'">'.
                                    '<input type="hidden" name="searched" value="'.$name.'">'.
                                    '<input type="submit" value="View Details" class="btn mt-3 my-submit-btn">';
                            echo    '</form>'.
                                    '</div>'.
                                    '</div>'.
                                    '<div class="container-fluid movie-title">'.$s->title.'</div>'.
                                    '</div>';
                            $num++;
                            if($num%4===0 || $num===count($SEARCH))
                                echo '</div>';
                            echo    '<script>'.
                                    '$("#'.$s->id.'").hover(function(){'.
                                    '$("#'.$s->id.'rating").fadeToggle("fast");'.
                                    '$("#'.$s->id.'released").fadeToggle("fast");'.
                                    '},function(){'.
                                    '$("#'.$s->id.'rating").fadeToggle("fast");'.
                                    '$("#'.$s->id.'released").fadeToggle("fast");'.
                                    '});'.
                                    '</script>';
                        }

                        echo    '<ul class="pagination justify-content-center mt-5">'.
                                '<li class="page-item">'.
                                '<form method="GET" action="mainPage.php">'.
                                '<button type="submit" class="page-link bg-theme"><i class="fa fa-backward"></i></button>'.
                                '<input type="hidden" name="searchInput" value="'.$name.'">'.
                                '<input type="hidden" name="pageno" value="1">';
                                if(isset($_GET['type']))
                                echo '<input type="hidden" name="type" value="'.$_GET['type'].'">';
                        echo    '</form>'.
                                '</li>';

                        
                            
                        if($page-1>0){
                                echo    '<li class="page-item">'.
                                    '<form method="GET" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">'.
                                    '<button type="submit" class="page-link bg-theme">'.($page-1).'</button>'.
                                    '<input type="hidden" name="searchInput" value="'.$name.'">'.
                                    '<input type="hidden" name="pageno" value="'.($page-1).'">';
                                if(isset($_GET['type']))
                                    echo '<input type="hidden" name="type" value="'.$_GET['type'].'">';
                            echo    '</form>'.
                                    '</li>';
                            }
                            echo    '<li class="page-item">'.
                                    '<form method="GET" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">'.
                                    '<button type="submit" class="page-link myActive">'.$page.'</button>'.
                                    '<input type="hidden" name="searchInput" value="'.$name.'">'.
                                    '<input type="hidden" name="pageno" value="'.$page.'">';
                            if(isset($_GET['type']))
                                    echo '<input type="hidden" name="type" value="'.$_GET['type'].'">';
                                    echo    '</form>'.
                                            '</li>';

                            if($page+1<=$file->total_pages){
                                echo    '<li class="page-item">'.
                                    '<form method="GET" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">'.
                                    '<button type="submit" class="page-link bg-theme">'.($page+1).'</button>'.
                                    '<input type="hidden" name="searchInput" value="'.$name.'">'.
                                    '<input type="hidden" name="pageno" value="'.($page+1).'">';
                                if(isset($_GET['type']))
                                    echo '<input type="hidden" name="type" value="'.$_GET['type'].'">';
                            echo    '</form>'.
                                    '</li>';
                            }
                        

                        echo    '<li class="page-item">'.
                                '<form method="GET" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">'.
                                '<button type="submit" class="page-link bg-theme"><i class="fa fa-forward"></i></button>'.
                                '<input type="hidden" name="searchInput" value="'.$name.'">'.
                                '<input type="hidden" name="pageno" value="'.$file->total_pages.'">';
                        if(isset($_GET['type']))
                        echo '<input type="hidden" name="type" value="'.$_GET['type'].'">';
                        echo    '</form>'.
                                '</li>';

                        echo    '</ul>';
                        echo '</div>';
                    }
                }
                else{
                    echo '<script>$("body").removeClass("addToClass");</script>';
                }

                    $urlDiscover='https://api.themoviedb.org/3/discover/movie?api_key=APIKEY&primary_release_year='.date('Y').'&sort_by=popularity.desc';
                    $search=json_decode(file_get_contents($urlDiscover));
                    // echo '<pre>';
                    // print_r($search);
                    // echo '</pre>';
                    $search=$search->results;
                    echo '<span class="container-fluid ml-3 text-theme mt-5 mb-2" style="display:inline-block; width:auto;"><i>Popular Movies of the Year :</i></span>';
                    echo '<div class="mb-5 myMarquee">';
                    foreach($search as $s){
                        echo    '<div class="marquee-movie-border" id="'.$s->id.'">';
                                if(isset($s->poster_path))
                                echo '<img src="'.$imageUrl.$s->poster_path.'" class="inner" style="position:absolute; height:100%; width:100%; display:inline;">';
                                else
                                echo '<h4 class="inner" style="display:inline;">Poster not available</h4>';
                        echo    '<div class="container-fluid movie-rating" id="'.$s->id.'rating" style="position:absolute; top:10%; text-align:center; display:none;">'.
                                '<i class="fa fa-star text-warning"></i><br>'.$s->vote_average.'/10'.
                                '</div>'.
                                '<div class="container-fluid movie-release" id="'.$s->id.'released" style="position:absolute; bottom:10%; text-align:center; display:none;">'.
                                $s->release_date.
                                '<br><form method="POST" action="movieDetails.php">'.
                                '<input type="hidden" name="id" value="'.$s->id.'">'.
                                '<input type="submit" value="View Details" class="btn mt-3 my-submit-btn">'.
                                '</form>'.
                                '</div>'.
                                '</div>';
    
                        echo    '<script>'.
                                '$("#'.$s->id.'").hover(function(){'.
                                '$("#'.$s->id.'rating").fadeToggle("fast");'.
                                '$("#'.$s->id.'released").fadeToggle("fast");'.
                                '},function(){'.
                                '$("#'.$s->id.'rating").fadeToggle("fast");'.
                                '$("#'.$s->id.'released").fadeToggle("fast");'.
                                '});'.
                                '</script>';
                        }
                    echo '</div>';
                ?>
    </body>
</html>
<script src='Plugin/jquery.marquee.min.js'></script>
<script src="Plugin/jquery.pause.min.js"></script>
<script>
    $(document).ready(function(){
        $('.myMarquee').marquee({
            pauseOnHover:true,
            delayBeforeStart:0
        });
    });
</script>