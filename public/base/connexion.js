var create_acc=document.querySelector(".create_acc");
var login_acc=document.querySelector(".login_acc");
var s_form=document.querySelectorAll(".s_form");
var login_button=document.querySelector(".login_button");
var signin_form_input=document.querySelectorAll(".signin_form input");

var signin_eye_click = document.querySelector(".fa-eye-slash");
var signin_type = document.querySelector(".signin_pass");
var set_signin_eye = document.querySelector(".fa-eye-slash");

var signup_eye_click = document.querySelector(".signup_eye");
var signup_type = document.querySelector(".signup_pass");
var set_signup_eye = document.querySelector(".signup_eye");

var signup_form_input=document.querySelectorAll(".signup_form input");
var signup_button=document.querySelector(".signup_button");


let formnumber=0;

create_acc.addEventListener('click',function(){
   formnumber++;
   create();
});

login_acc.addEventListener('click',function(){
   formnumber--;
   create();
});



function create(){
    s_form.forEach((form_num)=>{
       form_num.classList.add('d-none');
   });
   s_form[formnumber].classList.remove('d-none'); 
};


login_button.onclick=function(){
    signin_form_input.forEach((e)=>{
        if(e.value.length<1){
            e.classList.add('signin_warn');
        }
          
    });
};

signin_form_input.forEach((e)=>{
    e.addEventListener('keyup',function(){
       if(e.value.length<1){
           e.classList.add('signin_warn');
          
       } 
      
       else{
           e.classList.remove('signin_warn');
       }
    });
});



signup_button.onclick=function(){
    signup_form_input.forEach((signup_e)=>{
        if(signup_e.value.length<1){
            signup_e.classList.add('signup_warn');
        }
    });
};

signup_form_input.forEach((signup_e)=>{
    signup_e.addEventListener('keyup',function(){
       if(signup_e.value.length<1){
           signup_e.classList.add('signup_warn');
          
       } 
        else{
               signup_e.classList.remove('signup_warn');
           }
    });
});

document.getElementById('signin_eye_click').addEventListener('click', function() {
    var signin_type = document.getElementById('signin_type');
    var signin_eye = document.getElementById('signin_eye_click');
    
    if (signin_type.type === "password") {
        signin_type.type = "text";
        signin_eye.classList.remove('fa-eye-slash');
        signin_eye.classList.add('fa-eye');
    } else {
        signin_type.type = "password";
        signin_eye.classList.add('fa-eye-slash');
        signin_eye.classList.remove('fa-eye');
    }
});

   

var current_fs, next_fs, previous_fs; // champs de l'étape actuelle, de l'étape suivante et de l'étape précédente
var opacity;
var current = 1;
var steps = $("fieldset").length;

setProgressBar(current);

$(".next").click(function(){

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    // Ajoute la classe "active" à la prochaine étape du formulaire
    next_fs.show(); 

    // Masque l'étape actuelle avec style
    current_fs.animate({opacity: 0}, {
        step: function(now) {
            // pour les animations de style lors du changement d'étape
            opacity = 1 - now;

            current_fs.css({
                'display': 'none',
                'position': 'relative'
            });
            next_fs.css({'opacity': opacity});
        }, 
        duration: 600
    });
    setProgressBar(++current);
});

$(".previous").click(function(){

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    // Ajoute la classe "active" à l'étape précédente du formulaire
    previous_fs.show();

    // Masque l'étape actuelle avec style
    current_fs.animate({opacity: 0}, {
        step: function(now) {
            // pour les animations de style lors du changement d'étape
            opacity = 1 - now;

            current_fs.css({
                'display': 'none',
                'position': 'relative'
            });
            previous_fs.css({'opacity': opacity});
        }, 
        duration: 600
    });
    setProgressBar(--current);


function setProgressBar(curStep){
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar")
        .css("width",percent+"%")
        .html(percent+"%"); 
}
});
