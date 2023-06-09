$(document).ready(function() {
    // Handle tag button click event
    $('.tag-button').click(function() {
        var selectedTags = [];
        $('.tag-container input:checked').each(function() {
            selectedTags.push($(this).val());
        });

        // Redirect to the explore page with the selected tags
        window.location.href = 'explore.php?tags=' + selectedTags.join(',');
    });
});

$(document).ready(function() {
  $(".tag-button").click(function() {
    var selectedTags = $(this).data("tag");
    window.location.href = 'explore.php?tag=' + selectedTags;
  });
});

// Check if the button was previously marked as active and set the appropriate class
document.addEventListener('DOMContentLoaded', function() {
  for (var i = 1; i <= 3; i++) {
    var button = document.getElementById('button' + i);
    if (localStorage.getItem('activeButton') === 'button' + i) {
      button.classList.add('active');
    }
  }
});

// Add click event listeners to the buttons
document.addEventListener('click', function(event) {
  var target = event.target;
  if (target.classList.contains('tag-button')) {
    // Remove active class from all buttons
    var buttons = document.getElementsByClassName('tag-button');
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].classList.remove('active');
    }

    // Add active class to the clicked button
    target.classList.add('active');

    // Store the active button's ID in local storage
    localStorage.setItem('activeButton', target.id);
  }
});
