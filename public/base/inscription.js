document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('msform');
    const progressBar = document.getElementById('progressbar');
    const nextButtons = document.querySelectorAll('.next');
    const previousButtons = document.querySelectorAll('.previous');
    let currentStep = 0;

    function showStep(stepIndex) {
        const fieldsets = form.querySelectorAll('fieldset');
        fieldsets.forEach((fieldset, index) => {
            if (index === stepIndex) {
                fieldset.style.display = 'block';
            } else {
                fieldset.style.display = 'none';
            }
        });

        const progressBarItems = progressBar.querySelectorAll('li');
        progressBarItems.forEach((item, index) => {
            if (index === stepIndex) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    function validateStep(stepIndex) {
        // Implement your validation logic here
        return true; // For demonstration, always return true
    }

    function goToNextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function goToPreviousStep() {
        currentStep--;
        showStep(currentStep);
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', goToNextStep);
    });

    previousButtons.forEach(button => {
        button.addEventListener('click', goToPreviousStep);
    });

    showStep(currentStep);});
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;
    
    setProgressBar(current);
    
    $(".next").click(function(){
        
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        
        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
        
        //show the next fieldset
        next_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;
    
                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({'opacity': opacity});
            }, 
            duration: 500
        });
        setProgressBar(++current);
    });
    
    $(".previous").click(function(){
        
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        
        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        
        //show the previous fieldset
        previous_fs.show();
    
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;
    
                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({'opacity': opacity});
            }, 
            duration: 500
        });
        setProgressBar(--current);
    });
    
    function setProgressBar(curStep){
        var percent = parseFloat(100 / steps) * curStep;
        percent = percent.toFixed();
        $(".progress-bar")
          .css("width",percent+"%")   
    }
    
    $(".submit").click(function(){
        return false;
    })
    