function showWish(id){
    // $('.wish-img').removeClass('show');
    $('.modal-body').hide()
    setTimeout(()=>{
        $("#wishImg").attr("src",`/assets/images/ (${id}).jpg`)
    $('#modal-container').removeAttr('class').addClass('six');
    $('body').addClass('modal-active');
    },100)
    // $('.wish-img').addClass('show');
    setTimeout(()=>{
        $('.modal-body').fadeIn()
    },1000)
}

  
  $('#modal-container').click(function(e){
    console.log(e)
    if(e.target.classList.contains('modal-background')){
        $('.modal-body').hide()
        setTimeout(()=>{
            $(this).addClass('out');
            $('body').removeClass('modal-active');
        },100)
    }
    
  });