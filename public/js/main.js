$(function(){
  // initiate current page on page reload
  localStorage.setItem("current_page", 1);
  
  function filter(){
    var current_page = parseInt(localStorage.getItem('current_page'));
    var template = `
    <div class="movie-container">
      <div class="row"> 
        <div class="col-md-4 div-container">
          <img class='img-thumbnail' src="$featured_image" alt="">
        </div>    
        <div class="col-md-8">
          <h3 class="movie-header"><a href="#"> $title</a></h3>
          <span class="label label-primary">$release_date</span> 
          <span class="label label-info"> $length m</span>
          <p>$description</p>
        </div>
      </div>
    </div>
    `;

    var page_count_template = `
      <li class="$count_class"><a class="pg-count" href="#" data-page-count="$page_count">$count_value</a></li>
    `;
    var left_arrow = `<li class="$left_arrow_class" id="left-pg-arrow"><a href="#" id="mv-pg-left-arrow">&laquo;</a></li>`;
    var right_arrow = `<li class="$right_arrow_class" id="right-pg-arrow"><a  href="#" id="mv-pg-rt-arrow">&raquo;</a></li>`;

    var lang = $('#mv-language').val();
    var genre = $('#mv-genre').val();
    var sort = $('#mv-sort').val();
    var host  = window.location.host;


    var url = "//"+host+"/movies/filter";
    url += '/json_resp/language/'+ lang + '/genre/' + genre + '/sort/' + sort + '/page/' + current_page;
    $.ajax({
      type: "GET",
      url: url,
      dataType: 'json'
    }).done(function(data, textStatus, jQXHR){
      var show_data = '', count = 0, result, pages ;
      if (!_.isEmpty(data.count)) {
        count = parseInt(data.count);
        pages = (Math.floor((count - 1 ) / 2)) + 1;
        var pagination_template = '', partial ='', page_count_window = 3, per_page = 2, total_pages;
        total_pages = Math.floor(count / per_page);

        if (pages > 1) {
          if (pages > 2) {
            pagination_template += left_arrow;

            for (var i = 0; i < page_count_window; i++) {
              var temp = page_count_template;
              
              temp = temp.replace('$page_count', current_page + i);
              if (i==0) {
                temp = temp.replace('$count_class', 'active');
              }
              temp = temp.replace('$count_value', current_page + i);
              partial += temp;
            }
             
            pagination_template += partial;
            pagination_template += right_arrow;

            if (total_pages <= page_count_window) {
              pagination_template = pagination_template.replace('$right_arrow_class', 'disabled');
              pagination_template = pagination_template.replace('$left_arrow_class', 'disabled');
            }

            if(current_page == 1){
              pagination_template = pagination_template.replace('$left_arrow_class', 'disabled');
            }

            if ((total_pages - current_page) <= page_count_window) {
              pagination_template = pagination_template.replace('$right_arrow_class', 'disabled');
            }

            $('#mv-pagination').html(pagination_template);
          }
        }
      }

        
      if (_.size(data.movies) >= 1) {
        _.each(data.movies, function(movie){
          var temp = template;
          temp = temp.replace("$title", movie.title);
          temp = temp.replace("$description", movie.description);
          temp = temp.replace("$featured_image", "//"+host+"/"+movie.featured_image);
          temp = temp.replace("$release_date", movie.release_date);
          temp = temp.replace("$length", movie.length);
          show_data += temp;
        });
      }else{
        show_data = 'there is no data to be shown';
      }

      $('.movies-list').html(show_data);

    }).fail(function(jqXHR, textStatus, errorThrown){
      //  console.log("jqXHR", jqXHR);
      //  console.log("textStatus", textStatus);
      //  console.log("errorThrown", errorThrown);
      // console.log("Oops we encountered some problem ...");
    })
  }


  function check_if_disabled($ele){
    return $ele.hasClass('disabled')
  }


  $(document).ready(function(){
    $('#mv-language').on("change", function(){
      filter();
    });

    $('#mv-genre').on("change", function(){
      filter();
    });  

    $('#mv-sort').on("change", function(){
      filter();
    });   
    filter();   

    $("#datepicker").datepicker();
    $("#timepicker").timepicker({
      timeFormat: 'G:i',
      ampm: false
    });

    $('body').on('click', '#left-pg-arrow>a', function(e){
      e.preventDefault(true); 
      if (!check_if_disabled($(this).parent())) {
        var current_page = parseInt(localStorage.getItem('current_page'));
        current_page -= 1;
        localStorage.setItem('current_page', current_page);
        filter();
      }
    })

    $('body').on('click', '#right-pg-arrow>a', function(e){
      e.preventDefault(true); 
      if (!check_if_disabled($(this).parent())) {
        var current_page = parseInt(localStorage.getItem('current_page'));
        current_page += 1;
        localStorage.setItem('current_page', current_page);
        filter();
      }
    })

    $('body').on('click', '#mv-pagination>li>a.pg-count', function(e){
      e.preventDefault(true);
      $(this).data('page-count')
      localStorage.setItem('current_page', $(this).data('page-count'));
      // current_page = $(this).data('page-count');
       
      filter();
    })
  });



})