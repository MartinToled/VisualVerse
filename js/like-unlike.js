
$(document).ready(function() {
    $(document).on("click", ".like", function() {
      var likeButton = $(this);
      var postid = likeButton.attr("id");
      $.ajax({
        url: '../inc/likes-includes.php',
        type: 'POST',
        async: true,
        data: {
          'liked': 1,
          'postid': postid
        },
        success: function(response) {
          var result = JSON.parse(response);
          if (result.status === 'success') {
            likeButton.attr("src", "../images/filled.png");
            likeButton.removeClass("like").addClass("unlike");
            var likeCount = parseInt(likeButton.siblings("span").text());
            likeButton.siblings("span").text(result.likes_count);
          } else {
            console.error(result.message);
          }
        }
      });
    });

    $(document).on("click", ".unlike", function() {
      var unlikeButton = $(this);
      var postid = unlikeButton.attr("id");
      $.ajax({
        url: '../inc/likes-includes.php',
        type: 'POST',
        async: true,
        data: {
          'unliked': 1,
          'postid': postid
        },
        success: function(response) {
          var result = JSON.parse(response);
          if (result.status === 'success') {
            unlikeButton.attr("src", "../images/unfilled.png");
            unlikeButton.removeClass("unlike").addClass("like");
            var likeCount = parseInt(unlikeButton.siblings("span").text());
            unlikeButton.siblings("span").text(result.likes_count);
          } else {
            console.error(result.message);
          }
        }
      });
    });
  });
