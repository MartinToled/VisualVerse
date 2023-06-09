$(document).ready(function() {
    // Get all video elements
    var videos = document.querySelectorAll("video");
  
    // Loop through each video
    videos.forEach(function(video) {
      // Get the corresponding controls for each video
      var playButton = video.parentElement.querySelector(".playButton");
      var muteButton = video.parentElement.querySelector(".muteButton");
      var volumeControl = video.parentElement.querySelector(".volumeControl");
      var seekBar = video.parentElement.querySelector(".seek-bar");
  
      // Add event listeners to buttons
      playButton.addEventListener("click", togglePlay);
      muteButton.addEventListener("click", toggleMute);
      volumeControl.addEventListener("input", changeVolume);
      seekBar.addEventListener("input", changeSeekBar);
  
      // Function to toggle play/pause
      function togglePlay() {
        if (video.paused || video.ended) {
          video.play();
          playButton.innerHTML = "<i class='fas fa-pause'></i>";
        } else {
          video.pause();
          playButton.innerHTML = "<i class='fas fa-play'></i>";
        }
      }
  
      // Function to toggle mute/unmute
      function toggleMute() {
        if (video.muted) {
          video.muted = false;
          muteButton.innerHTML = "<i class='fas fa-volume-up'></i>";
        } else {
          video.muted = true;
          muteButton.innerHTML = "<i class='fas fa-volume-off'></i>";
        }
      }
  
      // Function to change volume
      function changeVolume() {
        video.volume = volumeControl.value;
  
        volumeControl.addEventListener("input", function() {
          const value = (this.value - this.min) / (this.max - this.min);
          const percent = value * 100;
          const backgroundColor = `linear-gradient(90deg, #46CDCF ${percent}%, #111111 ${percent}%)`;
  
          this.style.background = backgroundColor;
        });
      }
  
      // Function to change seek bar
      function changeSeekBar() {
        var seekTime = video.duration * (seekBar.value / 100);
        video.currentTime = seekTime;
      }
  
      // Update the seek bar as the video plays
      video.addEventListener("timeupdate", function() {
        var progress = (video.currentTime / video.duration) * 100;
        seekBar.value = progress;
      });
    });
  });

  function toggleFullscreen() {
    const video = document.getElementById('vid');
    if (video.requestFullscreen) {
      video.requestFullscreen();
    } else if (video.mozRequestFullScreen) { // Firefox
      video.mozRequestFullScreen();
    } else if (video.webkitRequestFullscreen) { // Chrome, Safari, and Opera
      video.webkitRequestFullscreen();
    } else if (video.msRequestFullscreen) { // IE/Edge
      video.msRequestFullscreen();
    }
  }