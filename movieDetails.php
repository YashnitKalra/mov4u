<?php
    $urlById='https://api.themoviedb.org/3/movie/'.$_POST['id'].'?api_key=APIKEY';
    $movie=json_decode(file_get_contents($urlById));

        // echo'<pre class="text-white">';
        // print_r($movie);
        // echo'</pre>';
    $videoUrl='http://api.themoviedb.org/3/movie/'.$_POST['id'].'/videos?api_key=APIKEY';
    $video=json_decode(file_get_contents($videoUrl));
        // echo'<pre class="text-white">';
        // print_r($video);
        // echo'</pre>';
    if(count($video->results)>0)
            $youtubeVideo='http://www.youtube.com/embed/'.$video->results[0]->key;
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $GLOBALS['movie']->title.' - movieE4U'; ?></title>
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
        <div class="bg-theme m-5 pb-5" style="background-color:rgba(219,157,71,0.6); border-radius:50px; position:relative;">
            <?php
                $mov=$GLOBALS['movie'];
                $url='https://api.themoviedb.org/3/search/movie?api_key=APIKEY&query=';
                $imageUrl='https://image.tmdb.org/t/p/w500';
                echo    '<div class="d-flex pl-3 pr-3 pt-3 bg-dark edit-bg">'.
                        '<span class="title mr-auto">'.$mov->title.' ('.substr($mov->release_date,0,4).')'.'</span>'.
                        '<span class="rating">'.
                        '<i class="fa fa-star text-warning"></i> '.$mov->vote_average.'/10 ('.$mov->vote_count.' Votes)'.
                        '</span>'.
                        '</div>'.
                        '<div class="d-flex pl-3 pb-3 bg-dark">'.
                        '<span class="info">';
                $i=0;
                foreach($mov->genres as $g){
                    if($i===count($mov->genres)-1)
                    echo $g->name;
                    else
                    echo $g->name.', ';
                    $i++;
                }
                echo    ' | '.$mov->runtime.' min | '.$mov->release_date.'</span>'.
                        '</div>';
                
                echo '<div class="row">';
                echo '<div class="col-sm-2">';
                echo '<img src="'.$imageUrl.$mov->poster_path.'" class="poster">';
                echo '</div>';
                echo '<div class="col-sm-10">';
                if(isset($GLOBALS['youtubeVideo']))
                echo '<iframe src="'.$GLOBALS['youtubeVideo'].'" allowfullscreen="allowfullscreen" class="video"></iframe>';
                else
                echo '<h3>Trailer not available</h3>';
                echo '</div></div>';

                echo '<p class="m-3 text-theme"><i>'.$mov->overview.'</i></p>';
                echo    '<div class="m-3 text-theme">'.
                        '<div>Production Companies : <i>';
                $i=0;
                foreach($mov->production_companies as $com){
                    if($i===count($mov->production_companies)-1)
                    echo $com->name;
                    else
                    echo $com->name.', ';
                    $i++;
                }
                echo    '</i></div>'.
                        '<br><div>Production Countries : <i>';
                $i=0;
                foreach($mov->production_countries as $con){
                    if($i===count($mov->production_countries)-1)
                    echo $con->name;
                    else
                    echo $con->name.', ';
                    $i++;
                }
                echo    '</i></div>'.
                        '<br><div>Languages : <i>';
                $i=0;
                foreach($mov->spoken_languages as $lang){
                    if($i===count($mov->spoken_languages)-1)
                    echo $lang->name;
                    else
                    echo $lang->name.', ';
                    $i++;
                }
                echo    '</i></div>';

                echo    '<br><div class="pb-5 text-theme">'.
                        '<div>Popularity : <i>'.$mov->popularity.'</i></div>'.
                        '</div></div>';

                        // echo '<pre>';
                        // print_r($mov);
                        // echo '</pre>';
                if(isset($_POST['searched'])){
                $name=$_POST['searched'];
                $search=json_decode(file_get_contents($url.$name));
                // echo '<pre>';
                // print_r($search);
                // echo '</pre>';
                $search=$search->results;
                
                if(count($search)>1){
                echo '<div class="container-fluid mb-2 ml-3 text-theme"><i>More :</i></div>';
                echo '<div class="mb-5 myMarquee">';
                foreach($search as $s){
                    if($mov->title===$s->title)
                    continue;
                    echo    '<div class="marquee-movie-border" id="'.$s->id.'">'.
                            '<img src="'.$imageUrl.$s->poster_path.'" class="inner" style="position:absolute; height:100%; width:100%; display:inline;">'.
                            '<div class="container-fluid movie-rating" id="'.$s->id.'rating" style="position:absolute; top:10%; text-align:center; display:none;">'.
                            '<i class="fa fa-star text-warning"></i><br>'.$s->vote_average.'/10'.
                            '</div>'.
                            '<div class="container-fluid movie-release" id="'.$s->id.'released" style="position:absolute; bottom:10%; text-align:center; display:none;">'.
                            $s->release_date.
                            '<br><form method="POST" action="movieDetails.php">'.
                            '<input type="hidden" name="id" value="'.$s->id.'">'.
                            '<input type="hidden" name="searched" value="'.$name.'">'.
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
                echo '</div>';}
            }
            ?>
        </div>
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