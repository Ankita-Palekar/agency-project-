<?php 
  $language_list = $result['language_list'];
  $genre_list = $result['genre_list'];
?>

<div class="container body-container" >
  <hr>

  <div class="row">
    <div class="col-md-6">
    <form name="language_form">
      <div class="form-group">
        <label for="for_language">Filter By Language</label>
        <select name="language" class="form-control" id="mv-language">
          <?php foreach ($language_list as $l) { ?>
            <option><?php print_r(ucwords($l['value']));?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="for_genre">Filter By Genre</label>
        <select name="genre" class="form-control" id="mv-genre">
          <?php foreach ($genre_list as $g) { ?>
            <option><?php print_r(ucwords($g['value']));?></option>
          <?php } ?>
        </select>
      </div>
    </form>
    </div>
    <div class="col-md-6">
      <form name="sorting_form">
      <div class="form-group">
        <label for="for_sort">Sort</label>
        <select name="sort_form" class="form-control" id="mv-sort">
          <option value="release_date.desc">Release Date</option>
          <option value="length.asc">Length</option>
        </select>
      </div>
      </form>
    </div>
  </div>  
  <hr>
  <div class="movies-list">
    There are no entries to be shown
    <div class="movie-container">
      <div class="row"> 
        <div class="col-md-4 div-container">
          <img class='img-thumbnail' src="$featured_image" alt="">
        </div>    
        <div class="col-md-8">
          <h3 class="movie-header"><a href=""> $title</a></h3>
          <span class="label label-primary">Release Dtae</span> <span class="label label-info"> length</span>
          <p>$description</p>
        </div>
      </div>
    </div>
  </div>


  <div class="pagination">
    <div class="row">
      <ul class="pagination" id="mv-pagination">
       <!--  <li><a href="#">&laquo;</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li> -->
      </ul>    
    </div>
  </div>
  
</div>