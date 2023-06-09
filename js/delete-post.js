// Get the delete buttons
const deleteButtons = document.querySelectorAll('.delete-post');

// Iterate over each delete button
deleteButtons.forEach(button => {
  button.addEventListener('click', function() {
    const postId = this.getAttribute('data-postid');

    // Display a confirmation dialog
    const result = window.confirm("Are you sure you want to delete this post?");

    // If the user confirms the deletion, proceed with the deletion
    if (result) {
      // Create a form dynamically
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../inc/delete-post.php';

      // Create an input field to hold the post ID
      const postIdInput = document.createElement('input');
      postIdInput.type = 'hidden';
      postIdInput.name = 'postid';
      postIdInput.value = postId;

      // Append the input field to the form
      form.appendChild(postIdInput);

      // Append the form to the document and submit it
      document.body.appendChild(form);
      form.submit();
    }
  });
});
