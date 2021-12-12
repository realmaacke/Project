$(document).ready(function () 
{
  $('[data-id]').click(function() 
  {
    var buttonid = $(this).attr('data-id');

      $.ajax({

            type: "POST",
            url: "api/likes?id=" + $(this).attr('data-id'),
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(r) 
            {
            var res = JSON.parse(r);
            $("[data-id='"+buttonid+"']").html(' '+res.Likes+' <i class="far fa-heart" data-aos="flip-right"></i><span></span>')
            },

            error: function(r) 
            {
                console.log(r)
            }
      
      });

  })

  $('#follow').click(function() 
  {
      $.ajax({

            type: "POST",
            url: "api/follow?user=" + $(this).val(),
            processData: false,
            contentType: "application/json",
            data: '',
            success: function(r) 
            {
              $('#follow').html(r);
            },

            error: function(r) 
            {
              console.log(r)
            }
      });
  })

  // this is the id of the form
  $("#commentForm").submit(function(e) 
    {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);

            $.ajax
            ({
                    type: "POST",
                    url: "api/comment",
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                    location.reload(); 
                    }
            });
    });

      // this is the id of the form
  $("#deletePost").submit(function(e) 
  {

  e.preventDefault(); // avoid to execute the actual submit of the form.

  var form = $(this);

          $.ajax
          ({
                  type: "POST",
                  url: "api/deletePost",
                  data: form.serialize(), // serializes the form's elements.
                  success: function(data)
                  {
                  location.reload(); 
                  }
          });
  });

  $("#deleteComment").submit(function(e) 
  {

  e.preventDefault(); // avoid to execute the actual submit of the form.

  var form = $(this);

          $.ajax
          ({
                  type: "POST",
                  url: "api/deleteComment",
                  data: form.serialize(), // serializes the form's elements.
                  success: function(data)
                  {
                  location.reload(); 
                  }
          });
  });

  $("button").click(function() {
      var commentValue = $(this).val(); 
    
      if(commentValue == "like")
      {
        return;
      } 
      else if(commentValue == "unlike")
      {
        return;
      }
      else 
      {
      row = $('#' + commentValue);

      //switching the css when true
      if(row.css('display') === 'none')
      {
      row.css('display', 'inline-block');
      }
      else if(row.css('display') === 'inline-block')
      {
        row.css('display', 'none');
      }
      else
      {
        return;
      }

    }
  });
});