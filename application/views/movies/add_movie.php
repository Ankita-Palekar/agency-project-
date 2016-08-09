<?php 
  $language_list = $result['language_list'];
  $genre_list = $result['genre_list'];
?>

<div class="container body-container" >
  <div class="row">
    <div class="col-md-12">
      <form enctype="multipart/form-data"  class="form-horizontal" action="//<?php echo $_SERVER['HTTP_HOST']?>/movies/add" method="POST">
        <h2 class="page-header">Add Your Movie</h2>
        <div class="form-group">
          <label for="title" class="col-sm-2 control-label">Title</label>
          <div class="col-sm-6">
            <input name="title" type="text" class="form-control" required="true">
          </div>
        </div>
        <div class="form-group">
          <label for="description" class="col-sm-2 control-label">Description</label>
          <div class="col-sm-6">
            <textarea name="description" class="form-control" rows="6" required="true">
            </textarea>
          </div>
        </div>

        <div class="form-group">
          <label for="length" class="col-sm-2 control-label">Length</label>
          <div class="col-sm-2">
            <input name="length" type="number" class="form-control" required="true">
          </div>
        </div>

        <div class="form-group">
          <label for="langauges" class="col-sm-2 control-label">Languages</label>
          <div class="col-sm-4">
            <select name="language_id" class="form-control" id="mv-language">
              <?php foreach ($language_list as $l) { ?>
                <?php echo "<option value='".ucwords($l['id'])."'>".ucwords($l['value'])."</option>"; ?>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="genres" class="col-sm-2 control-label">Genres</label>
          <div class="col-sm-4">
            <select name="genre_id" class="form-control" id="mv-genre">
              <?php foreach ($genre_list as $g) { ?>
                <?php echo "<option value='".ucwords($g['id'])."'>".ucwords($g['value'])."</option>"; ?>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="release_date" class="col-sm-2 control-label">Release Date</label>
          <div class="col-sm-2">
            <input type="text" name="release_date" class="form-control" id="datepicker" required="true">
          </div>
          <div class="col-sm-2">
            <input type="text" name="release_time" class="form-control" id="timepicker" required="true">
          </div>
          <div class="col-sm-2">            
          </div>
        </div>
          
        <div class="form-group">
          <label for="choose image" class="col-sm-2 control-label">Choose Image</label>
          <div class="col-sm-2">
            <input name="featured_image" type="file" required="true"> 
          </div>
        </div>
        <div class="form-group"> 
          <label for="" class="col-sm-2 control-label"></label>
          <div class="col-sm-2">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

