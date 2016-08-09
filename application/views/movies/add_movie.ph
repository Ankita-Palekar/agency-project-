<?php 
  $language_list = $result['language_list'];
  $genre_list = $result['genre_list'];
?>

<div class="container body-container" >
  <div class="row">
    <div class="col-md-12">
      <form class="form-horizontal" action="../../../add_movie" method="POST">
        <h2 class="page-header">Add Your Movie</h2>
        <div class="form-group">
          <label for="title" class="col-sm-2 control-label">Title</label>
          <div class="col-sm-10">
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label for="description" class="col-sm-2 control-label">Description</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="3">
            </textarea>
          </div>
        </div>

        <div class="form-group">
          <label for="length" class="col-sm-2 control-label">Length</label>
          <div class="col-sm-2">
            <input type="number" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label for="langauges" class="col-sm-2 control-label">Languages</label>
          <div class="col-sm-4">
            <select name="language" class="form-control" id="mv-language">
              <?php foreach ($language_list as $l) { ?>
                <option><?php print_r(ucwords($l));?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="genres" class="col-sm-2 control-label">Genres</label>
          <div class="col-sm-4">
            <select name="genres" class="form-control" id="mv-genre">
              <?php foreach ($genre_list as $g) { ?>
                <option><?php print_r(ucwords($g));?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="release_date" class="col-sm-2 control-label">Release Date</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" id="datepicker">
          </div>
          <div class="col-sm-2">            
          </div>
        </div>
          
        <div class="form-group">
          <label for="choose image" class="col-sm-2 control-label">Choose Image</label>
          <div class="col-sm-2">
            <input type="file">
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

