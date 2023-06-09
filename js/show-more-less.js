$(document).ready(function() {
    $(".show-more-button").click(function() {
      var $commentsContainer = $(this).closest("#Posts").find("#comments");
      $commentsContainer.find(".hidden-comment").removeClass("hidden-comment");
      $commentsContainer.find(".show-more-button").hide();
      $commentsContainer.find(".show-less-button").show();
    });

    $(".show-less-button").click(function() {
      var $commentsContainer = $(this).closest("#Posts").find("#comments");
      $commentsContainer.find(".comment:nth-of-type(n+4)").addClass("hidden-comment");
      $commentsContainer.find(".show-more-button").show();
      $commentsContainer.find(".show-less-button").hide();
    });
  });

  //When user scrolls down, nav bar goes up, and when user scrolls up a bit, it will pop back up
  var prevScrollpos = window.pageYOffset;
  window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
      document.getElementById("top-nav").style.top = "0";
    } else {
      document.getElementById("top-nav").style.top = "-20vw";
    }
    prevScrollpos = currentScrollPos;
    }