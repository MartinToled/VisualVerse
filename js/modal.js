    $(document).ready(function() {

    $('#pfp').click(function() {
        $('#myModal').css('display', 'block');
        $('#imgModal').attr('src', $(this).attr('src'));
    });

    // Open modal when header picture is clicked
    $('#header').click(function() {
        $('#myModal').css('display', 'block');
        $('#imgModal').attr('src', $(this).attr('src'));
    });
    // Open modal when post picture is clicked
    $('.post-img').click(function() {
        $('#myModal').css('display', 'block');
        $('#imgModal').attr('src', $(this).attr('src'));
    });

    // Close modal when the close button or outside the modal is clicked
    $('.close, .modal').click(function() {
        $('#myModal').css('display', 'none');
    });

    
});

function readURL(input, imgId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#' + imgId)
                .attr('src', e.target.result)
                .width(200)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

