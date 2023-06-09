$(document).on("click", ".edit-post", function() {
    var postid = $(this).data("postid");
    window.location.href = "../inc/edit-post.php?postid=" + postid;
  });