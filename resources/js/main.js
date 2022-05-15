

// li:active in nabvbar
$(document).ready(function(){
   addnav()
});

function addnav()
{
   $('.navbar-nav .nav-item a').click(function()
   {
       $('.nav-item').removeClass('active') 
       $(this).closest('.nav-item').addClass('active') 

   })
}


//change navbar color
$(document).ready(function(){
  $(window).scroll(function(){
  	var scroll = $(window).scrollTop();
	  if (scroll > 300) {
	    $(".navbar").css("background" , "blue");
	  }
	  else{
		  $(".navbar").css("background" , "#333");  	
	  }
  })
})


//change background navbar while scrolling 
$(function()
{
   var scroll = $(document).scrollTop() ;
   var navHeight = $('.nav-area').outerHeight(); 
   $(window).scroll(function() {
      var scrolled = $(document).scrollTop(); 

      if ( scrolled > navHeight ) {
          $('.nav-area').addClass('animate') ;
      }
      else {
        $('.nav-area').removeClass('animate') ;
      }

      if(scrolled > scroll ){
        $('.nav-area').removeClass('sticky') ;
      }
      else{
        $('.nav-area').addClass('sticky') ;
      }
      scroll = $(document).scrollTop(); 
   });

});




//  typer.js   
var options = {
    strings: ["Designer", "developer"],
    typeSpeed: 70,
    fadeOut: false,
    loop: true,
    showCursor: true,
    smartBackspace:true,
    backSpeed: 30
};
var typed = new Typed('.element', options);

  


  // jQuery counterUp   
  $('[data-toggle="counter-up"]').counterUp({
    delay: 10,
    time: 1000
  });



 // Portfolio isotope filter   
 $(document).ready(function(){
    $('.portfolio-item').isotope(function(){
        itemSelector:'.item'
      });
  
    $('.portfolio-menu ul li').click(function(){
      $('.portfolio-menu ul li').removeClass('active');
      $(this).addClass('active');
  
  
      var selector = $(this).attr('data-filter');
        $('.portfolio-item').isotope({
          filter: selector
        })
        return false;
    });
  });


  
// image details form 

var modal = document.getElementById('id01');

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}