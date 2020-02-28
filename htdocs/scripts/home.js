$(function(){
  $(".menu-contacts").hide();

  $(".menu-close").click(function() {
    $(".contact-list").hide(50);
    $(".menu-contacts").animate({width: 'toggle'}, 180, function() {
      $(".contact-list").show();
    });
  });

  $("#new-post-form").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var form = $(this);

    $.ajax({
      type: "POST",
      url: form.attr('action'),
      data: formData,
      dataType: 'JSON',

      success: function (data) {
        console.log('Submission was successful.');
        console.log(data);
        var error = data["error"];
        if (error == null) {
          // No errors on ajax request
          // PROCEED
          var new_post = data["new_post"];
          $("#feed ul").prepend(new_post);
        } else {
          // Error ocurred, show it?
          alert(error);
        }
      },

      error: function (data) {
        console.log('An error occurred.');
        console.log(data);
      },
      cache: false,
      contentType: false,
      processData: false
    });

    document.getElementById("new-post-picture").value = "";
    document.getElementById("new-post-textarea").value = "";
    $("#post-picture-preview").attr("src", "");
    $("#post-picture-preview").hide();
  });

  $("#new-post-picture").change(function() {
    readURL(this, "#post-picture-preview");
    $("#post-picture-preview").show(300);
  });

  $("#feed").on('click', '.post-roses', function() {
    var postID = $(this).attr("post-id");
    var rosesSpan = $(this).find('span');
    var current_roses = +rosesSpan.text() + 1;

    $.ajax({
      type: "POST",
      data: {"post_id": postID} ,
      url: "ajax/upvote.php",
      dataType: 'JSON',

      success: function (data) {
        console.log('Vote posted.');
        console.log(data);

        if(data["error"] != "failed") {
          rosesSpan.text(current_roses);
        }
      },

      error: function (data) {
        console.log('Vote could not be posted.');
        console.log(data);
      }
    });
  });

  $("#feed").on('click', '.comment-roses', function() {
    var commentID = $(this).attr("comment-id");
    var rosesSpan = $(this).find('span');
    var current_roses = + rosesSpan.text() + 1;

    $.ajax({
      type: "POST",
      data: {"comment_id": commentID} ,
      url: "ajax/up_comment.php",
      dataType: 'JSON',

      success: function (data) {
        console.log('Vote posted.');
        console.log(data);

        if(data["error"] != "failed") {
          rosesSpan.text(current_roses);
        }
      },

      error: function (data) {
        console.log('Vote could not be posted.');
        console.log(data);
      }
    });
  });

  $("#feed").on('submit', '.new-comment-form', function (e) {
      e.preventDefault();
      var form = $(this);

      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'JSON',

        success: function (data) {
          console.log('Submission was successful.');
          console.log(data);
          var error = data["error"];
          if (error == null) {
            // No errors on ajax request
            // PROCEED
            var new_comment = data["new_comment"];
            form.parent().prev().after(new_comment);
          } else {
            // Error ocurred, show it?
            alert(error);
          }
        },

        error: function (data) {
          console.log('An error occurred.');
          console.log(data);
        }
      });

      form.find(".new-comment-input").val("");
    });
});
