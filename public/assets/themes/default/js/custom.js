//Check to see if the window is top if not then display button
jQuery(window).scroll(function(){
  if (jQuery(this).scrollTop() > 300) {
    jQuery('.scrollToTop').fadeIn();
  } else {
    jQuery('.scrollToTop').fadeOut();
  }
});


 
//Click event to scroll to top

jQuery('.scrollToTop').click(function(){
  jQuery('html, body').animate({scrollTop : 0},800);
  return false;
});  
//end Click event to scroll to top

// Show modal with animation after page loads
document.addEventListener('DOMContentLoaded', function() {
    // Create modal instance
    var myModal = new bootstrap.Modal(document.getElementById('franchiseModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    // Small delay to ensure animations work properly
    setTimeout(function() {
        myModal.show();
    }, 500);
    
    // Optional: Add animation class when showing
    document.getElementById('franchiseModal').addEventListener('show.bs.modal', function() {
        this.querySelector('.modal-dialog').classList.add('animate__animated', 'animate__fadeInUp');
    });
});



