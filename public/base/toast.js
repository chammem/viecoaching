
    window.onload = function() {
        var toast = document.querySelector('.toast');
        var closeToastButton = document.querySelector('.close-toast');
        
        // Disappear after 3 seconds
        setTimeout(function() {
            toast.style.display = 'none';
        }, 6000);

        // Disappear when the close button is clicked
        closeToastButton.addEventListener('click', function() {
            toast.style.display = 'none';
        });
    };
