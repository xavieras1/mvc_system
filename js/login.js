$(document).ready(function() {
    //Slide SOURCE: http://www.htmlforums.com/css/t-can-i-use-css-to-change-image-after-regular-intervel-of-time-152366.html
    //Get url SOURCE: http://stackoverflow.com/questions/406192/how-to-get-the-current-url-in-jquery
    //search content str SOURCE: http://stackoverflow.com/questions/1789945/method-like-string-contains-in-javascript
    /******************SLIDES DEL LOGIN*****************/
    var rotator = document.getElementById('rotator');  // change to match image ID
    var imageDir = 'img/';                          // change to match images folder
    var delayInSeconds = 5;                            // set number of seconds delay
    // list image names
    var images = ['slide2.jpg', 'slide3.jpg', 'slide1.jpg'];

    // don't change below this line
    var num = 0;
    var changeImage = function() {
        var len = images.length;
        rotator.src = imageDir + images[num++];
        if (num == len) {
            num = 0;
        }
    };
    setInterval(changeImage, delayInSeconds * 1000);
    /*********************fin slides*********************/
    
    var url = window.location.pathname;
    var msg=url.indexOf("error")
    if(msg!=-1)
        alert(url.substring(msg+6));
});